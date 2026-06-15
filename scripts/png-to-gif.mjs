import zlib from "node:zlib";

const palette = [];
const add = (r, g, b) => palette.push([r, g, b]);

add(0, 0, 0);
add(24, 45, 91);
add(22, 46, 92);
add(255, 255, 255);
add(240, 166, 36);
add(240, 166, 37);
add(216, 107, 27);
add(87, 42, 20);
add(69, 158, 58);
add(249, 244, 232);
add(255, 250, 240);
add(18, 28, 54);
add(40, 58, 104);

for (let r = 0; r < 6; r += 1) {
  for (let g = 0; g < 6; g += 1) {
    for (let b = 0; b < 6; b += 1) add(r * 51, g * 51, b * 51);
  }
}

while (palette.length < 256) add(0, 0, 0);

function parsePng(buffer) {
  let offset = 8;
  let width = 0;
  let height = 0;
  let colorType = 6;
  let bitDepth = 8;
  const idat = [];

  while (offset < buffer.length) {
    const length = buffer.readUInt32BE(offset);
    offset += 4;
    const type = buffer.toString("ascii", offset, offset + 4);
    offset += 4;
    const data = buffer.subarray(offset, offset + length);
    offset += length + 4;

    if (type === "IHDR") {
      width = data.readUInt32BE(0);
      height = data.readUInt32BE(4);
      bitDepth = data[8];
      colorType = data[9];
    } else if (type === "IDAT") {
      idat.push(data);
    } else if (type === "IEND") {
      break;
    }
  }

  if (bitDepth !== 8 || ![2, 6].includes(colorType)) {
    throw new Error(`Unsupported PNG format: bitDepth=${bitDepth}, colorType=${colorType}`);
  }

  const bpp = colorType === 6 ? 4 : 3;
  const raw = zlib.inflateSync(Buffer.concat(idat));
  const stride = width * bpp;
  const rgba = Buffer.alloc(width * height * 4);
  let pos = 0;
  let previous = Buffer.alloc(stride);

  for (let y = 0; y < height; y += 1) {
    const filter = raw[pos];
    pos += 1;
    const scanline = Buffer.from(raw.subarray(pos, pos + stride));
    pos += stride;

    for (let x = 0; x < stride; x += 1) {
      const left = x >= bpp ? scanline[x - bpp] : 0;
      const up = previous[x] || 0;
      const upLeft = x >= bpp ? previous[x - bpp] : 0;
      let value = scanline[x];

      if (filter === 1) value = (value + left) & 255;
      else if (filter === 2) value = (value + up) & 255;
      else if (filter === 3) value = (value + Math.floor((left + up) / 2)) & 255;
      else if (filter === 4) {
        const p = left + up - upLeft;
        const pa = Math.abs(p - left);
        const pb = Math.abs(p - up);
        const pc = Math.abs(p - upLeft);
        value = (value + (pa <= pb && pa <= pc ? left : pb <= pc ? up : upLeft)) & 255;
      }

      scanline[x] = value;
    }

    for (let x = 0; x < width; x += 1) {
      const source = x * bpp;
      const target = (y * width + x) * 4;
      rgba[target] = scanline[source];
      rgba[target + 1] = scanline[source + 1];
      rgba[target + 2] = scanline[source + 2];
      rgba[target + 3] = bpp === 4 ? scanline[source + 3] : 255;
    }

    previous = scanline;
  }

  return { width, height, rgba };
}

function closestPaletteIndex(r, g, b, a) {
  if (a < 32) return 0;
  let best = 1;
  let bestDistance = Infinity;

  for (let i = 1; i < 256; i += 1) {
    const color = palette[i];
    const dr = r - color[0];
    const dg = g - color[1];
    const db = b - color[2];
    const distance = dr * dr + dg * dg + db * db;
    if (distance < bestDistance) {
      bestDistance = distance;
      best = i;
    }
  }

  return best;
}

function bytesFromString(value) {
  return Array.from(value, (char) => char.charCodeAt(0) & 255);
}

function word(value) {
  return [value & 255, (value >> 8) & 255];
}

function subBlocks(data) {
  const output = [];
  for (let i = 0; i < data.length; i += 255) {
    const chunk = data.slice(i, i + 255);
    output.push(chunk.length, ...chunk);
  }
  output.push(0);
  return output;
}

function lzwEncode(indices) {
  const clear = 256;
  const end = 257;
  const dictionary = new Map();
  for (let i = 0; i < 256; i += 1) dictionary.set(String(i), i);

  const codes = [clear];
  let prefix = String(indices[0]);
  let nextCode = 258;
  let codeSize = 9;

  for (let i = 1; i < indices.length; i += 1) {
    const key = indices[i];
    const combo = `${prefix},${key}`;
    if (dictionary.has(combo)) {
      prefix = combo;
    } else {
      codes.push(dictionary.get(prefix));
      if (nextCode < 4096) {
        dictionary.set(combo, nextCode);
        nextCode += 1;
        if (nextCode === 1 << codeSize && codeSize < 12) codeSize += 1;
      }
      prefix = String(key);
    }
  }

  codes.push(dictionary.get(prefix), end);

  const bits = [];
  codeSize = 9;
  nextCode = 258;

  for (const code of codes) {
    for (let bit = 0; bit < codeSize; bit += 1) bits.push((code >> bit) & 1);

    if (code === clear) {
      codeSize = 9;
      nextCode = 258;
    } else if (code !== end) {
      nextCode += 1;
      if (nextCode === 1 << codeSize && codeSize < 12) codeSize += 1;
    }
  }

  const output = [];
  for (let i = 0; i < bits.length; i += 8) {
    let value = 0;
    for (let bit = 0; bit < 8; bit += 1) {
      if (bits[i + bit]) value |= 1 << bit;
    }
    output.push(value);
  }

  return output;
}

export function encodeGifFromPngs(pngBuffers, delayCs = 5) {
  const decodedFrames = pngBuffers.map((buffer) => parsePng(Buffer.from(buffer)));
  const { width, height } = decodedFrames[0];

  const indexedFrames = decodedFrames.map((frame) => {
    if (frame.width !== width || frame.height !== height) {
      throw new Error("All PNG frames must have the same dimensions.");
    }

    const indices = new Uint8Array(width * height);
    for (let source = 0, target = 0; source < frame.rgba.length; source += 4, target += 1) {
      indices[target] = closestPaletteIndex(
        frame.rgba[source],
        frame.rgba[source + 1],
        frame.rgba[source + 2],
        frame.rgba[source + 3],
      );
    }
    return indices;
  });

  const output = [];
  output.push(...bytesFromString("GIF89a"), ...word(width), ...word(height), 0xf7, 0, 0);
  for (const color of palette) output.push(color[0], color[1], color[2]);
  output.push(0x21, 0xff, 11, ...bytesFromString("NETSCAPE2.0"), 3, 1, 0, 0, 0);

  for (const frame of indexedFrames) {
    output.push(0x21, 0xf9, 4, 0x09, ...word(delayCs), 0, 0);
    output.push(0x2c, 0, 0, 0, 0, ...word(width), ...word(height), 0);
    output.push(8, ...subBlocks(lzwEncode(frame)));
  }

  output.push(0x3b);
  return { buffer: Buffer.from(output), width, height };
}
