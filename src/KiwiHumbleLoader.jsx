import React, { useEffect, useRef, useState } from "react";
import "./kiwi-humble-loader.css";

const DEFAULT_LOTTIE_PATH = "../assets/lottie/kiwi-humble-loader-2027.json";
const DEFAULT_ASSETS_PATH = "../assets/svg/";
const DEFAULT_FALLBACK_SRC = "../assets/svg/kiwi-humble-loader-fallback.svg";

function KiwiHumbleFallback({ label, fallbackSrc }) {
  return (
    <img className="kh-loader-svg" src={fallbackSrc} alt="" aria-hidden="true" data-label={label} />
  );
}

export default function KiwiHumbleLoader({
  size = 160,
  label = "Loading KIWI HUMBLE",
  autoplay = true,
  loop = true,
  className = "",
  fallback = "css",
  animationPath = DEFAULT_LOTTIE_PATH,
  assetsPath = DEFAULT_ASSETS_PATH,
  animationData,
  fallbackSrc = DEFAULT_FALLBACK_SRC,
}) {
  const containerRef = useRef(null);
  const animationRef = useRef(null);
  const [useFallback, setUseFallback] = useState(false);

  useEffect(() => {
    let cancelled = false;

    if (!containerRef.current) {
      return undefined;
    }

    async function mountLottie() {
      try {
        const lottieModule = await import("lottie-web");
        const lottie = lottieModule.default || lottieModule;

        if (cancelled || !containerRef.current) return;

        animationRef.current = lottie.loadAnimation({
          container: containerRef.current,
          renderer: "svg",
          loop,
          autoplay,
          path: animationData ? undefined : animationPath,
          animationData,
          assetsPath,
        });

        animationRef.current.addEventListener("data_failed", () => {
          if (!cancelled && fallback === "css") setUseFallback(true);
        });
      } catch {
        if (!cancelled && fallback === "css") setUseFallback(true);
      }
    }

    setUseFallback(false);
    mountLottie();

    return () => {
      cancelled = true;
      if (animationRef.current) {
        animationRef.current.destroy();
        animationRef.current = null;
      }
    };
  }, [animationData, animationPath, assetsPath, autoplay, fallback, loop]);

  const style = {
    "--kh-loader-size": typeof size === "number" ? `${size}px` : size,
  };

  return (
    <span className={`kh-loader ${className}`.trim()} style={style} role="status" aria-live="polite">
      <span className="kh-loader__sr">{label}</span>
      {useFallback ? (
        <KiwiHumbleFallback label={label} fallbackSrc={fallbackSrc} />
      ) : (
        <span className="kh-loader__lottie" ref={containerRef} aria-hidden="true" />
      )}
    </span>
  );
}
