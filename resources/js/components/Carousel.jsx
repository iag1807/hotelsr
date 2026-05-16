import React, { useState, useEffect } from "react";

const slides = [
  { bg: "/images/moño2.jpg", frase: "Tu descanso, nuestra prioridad." },
  { bg: "/images/sencilla2.jpg", frase: "Siente la tranquilidad que mereces." },
  { bg: "/images/moño.jpeg", frase: "Un lugar para desconectar del mundo." },
];

export const Carousel = () => {
  const [current, setCurrent] = useState(0);

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrent((prev) => (prev + 1) % slides.length);
    }, 4000);
    return () => clearInterval(timer);
  }, []);

  return (
    <section className="contenedor-carrusel">
      <div className="carousel-slides" id="slides">
        {slides.map((slide, index) => (
          <div
            key={index}
            className="slide"
            style={{
              display: index === current ? "flex" : "none",
              backgroundImage: `url("${slide.bg}"), linear-gradient(rgba(37,37,37,0.9), rgba(38,38,38,0.9))`,
            }}
          >
            <div className="slide-content">
              <h1>
                {slide.frase}
              </h1>
              <a href="#habitaciones" className="btn">
                Haz tu reserva
              </a>
            </div>
          </div>
        ))}
      </div>

      <div className="carousel-indicators" id="indicators">
        {slides.map((_, index) => (
          <span
            key={index}
            className={`indicator ${index === current ? "active" : ""}`}
            onClick={() => setCurrent(index)}
          />
        ))}
      </div>
    </section>
  );
};