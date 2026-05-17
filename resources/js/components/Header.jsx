import React, { useState, useEffect } from "react";

export const Header = () => {
  const [scrolled, setScrolled] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);

  useEffect(() => {
    const handleScroll = () => setScrolled(window.scrollY > 600);
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const closeMenu = () => setMenuOpen(false);
  const toggleMenu = () => setMenuOpen((prev) => !prev);

  return (
    <>
      <header className={scrolled ? "header-scrolled" : ""}>
        <div className="container">
          <div className="header-inner">

            {/* Left nav — desktop */}
            <div className="header-left">
              <nav>
                <ul>
                  <li><a href="#habitaciones"><button className="header-btn">Habitaciones</button></a></li>
                  <li><a href="#sobre-nosotros"><button className="header-btn">Sobre nosotros</button></a></li>
                  <li><a href="#ubicacion"><button className="header-btn">Ubicación</button></a></li>
                </ul>
              </nav>
            </div>

            {/* Center logo */}
            <div className="header-center">
              <img className="logo" src="/images/logo.png" alt="Hotel Sueño Real" />
            </div>

            {/* Right nav — desktop */}
            <div className="header-right">
              <nav>
                <ul>
                  <li><a href="#reseñas"><button className="header-btn">Reseñas</button></a></li>
                  <li><a href="/login"><button className="header-btn">Iniciar sesión</button></a></li>
                  <li><a href="/register"><button className="header-btn">Registrarse</button></a></li>
                </ul>
              </nav>
            </div>

            {/* Hamburger — mobile/tablet */}
            <button
              className={`hamburger-btn${menuOpen ? " is-open" : ""}`}
              onClick={toggleMenu}
              aria-label={menuOpen ? "Cerrar menú" : "Abrir menú"}
              aria-expanded={menuOpen}
            >
              <span className="hamburger-line" />
              <span className="hamburger-line" />
              <span className="hamburger-line" />
            </button>

          </div>
        </div>
      </header>

      {/* Backdrop */}
      <div
        className={`mobile-menu-backdrop${menuOpen ? " mobile-menu--open" : ""}`}
        onClick={closeMenu}
      />

      {/* Panel dropdown desde el top */}
      <div
        className={`mobile-menu${menuOpen ? " mobile-menu--open" : ""}`}
        role="dialog"
        aria-label="Menú de navegación"
      >
        {/* Cabecera: X a la izquierda, logo centrado */}
        <div className="mobile-menu-header">
          <button className="mobile-menu-close" onClick={closeMenu} aria-label="Cerrar menú">
            ✕
          </button>
          <div className="mobile-menu-header-logo-wrap">
            <img className="mobile-menu-logo" src="/images/logo.png" alt="Hotel Sueño Real" />
            <span className="mobile-menu-brand">Hotel Sueño Real</span>
          </div>
        </div>

        {/* Links de navegación */}
        <nav>
          <ul>
            <li><a href="#habitaciones" onClick={closeMenu}>Habitaciones</a></li>
            <li><a href="#sobre-nosotros" onClick={closeMenu}>Sobre nosotros</a></li>
            <li><a href="#ubicacion" onClick={closeMenu}>Ubicación</a></li>
            <li><a href="#reseñas" onClick={closeMenu}>Reseñas</a></li>
          </ul>
        </nav>

        {/* Botones de sesión */}
        <div className="mobile-menu-footer">
          <a href="/login" className="btn-login" onClick={closeMenu}>Iniciar sesión</a>
          <a href="/register" className="btn-register" onClick={closeMenu}>Registrarse</a>
        </div>
      </div>
    </>
  );
};