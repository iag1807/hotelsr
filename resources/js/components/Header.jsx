import React, { useState, useEffect } from "react";

export const Header = () => {
  const [scrolled, setScrolled] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 600);
    };

    window.addEventListener("scroll", handleScroll);

    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <header className={scrolled ? "header-scrolled" : ""}>
      <div className="container">
        <div className="header-inner">
          <div className="header-left">
            <nav>
              <ul>
                <li>
                  <a href="#habitaciones">
                    <button className="header-btn">Habitaciones</button>
                  </a>
                </li>
                <li>
                  <a href="#sobre-nosotros">
                    <button className="header-btn">Sobre nosotros</button>
                  </a>
                </li>
                <li>
                  <a href="#ubicacion">
                    <button className="header-btn">Ubicación</button>
                  </a>
                </li>
              </ul>
            </nav>
          </div>

          <div className="header-center">
            <img
              className="logo"
              src="/images/logo.png"
              alt="Hotel Sueño Real"
            />
          </div>

          <div className="header-right">
            <nav>
              <ul>
                <li>
                  <a href="#reseñas">
                    <button className="header-btn">Reseñas</button>
                  </a>
                </li>
                <li>
                  <a href="/login">
                    <button className="header-btn">Iniciar sesión</button>
                  </a>
                </li>
                <li>
                  <a href="/register">
                    <button className="header-btn">Registrarse</button>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </header>
  );
};