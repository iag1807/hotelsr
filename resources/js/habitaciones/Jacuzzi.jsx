import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import "../css/habitaciones.css";

const images = [
  "/images/jacuzzi.jpeg",
  "/images/imagen.jpg",
  "/images/baño3.jpg",
  "/images/jacuzzi2.jpg",
  "/images/jacuzzi3.jpg",
  "/images/imagen2.jpg",
  "/images/jacuzzi1.jpeg",
];

const servicios = [
  "WiFi gratuito",
  "Televisor pantalla plana",
  "Baño privado",
  "Servicio de limpieza",
  "Jacuzzi",
  "Mini bar",
];

export const Jacuzzi = () => {
  const [mainImage, setMainImage] = useState(images[0]);
  const navigate = useNavigate();

  return (
    <div className="habitacion-page">
      <header className="hab-header">
        <img className="logo" src="/images/logo.png" alt="Logo Hotel" />
        <div className="seccion-header">
          <h1 className="titulo">
            HABITACIÓN <span>CON JACUZZI</span>
          </h1>
          
            <a href="/"><button className="volver-btn">Volver</button></a>
          
        </div>
      </header>

      <div className="contenedor">
        <div className="seccion-galeria">
          <div className="imagen-principal">
            <img src={mainImage} alt="Habitación Con Jacuzzi" loading="lazy" />
          </div>
          <div className="imagenes-secundarias">
            {images.slice(1).map((img, i) => (
              <div
                className={`imagen ${mainImage === img ? "activa" : ""}`}
                key={i}
                onClick={() => setMainImage(img)}
              >
                <img src={img} alt={`Vista ${i + 1}`} loading="lazy" />
              </div>
            ))}
          </div>
        </div>

        <div className="seccion-detalles">
          <h2>DETALLES</h2>

          <div className="detalles-habitacion">
            <div className="detalles">
              <img className="icono-razon" src="/images/icono-cama.png" alt="Cama" />
              <div className="detalles-text">
                <h3>Capacidad</h3>
                <p>2 personas · Cama semidoble</p>
              </div>
            </div>
            <div className="detalles">
              <img className="icono-razon" src="/images/icono-ambiente.png" alt="Ambiente" />
              <div className="detalles-text">
                <h3>Ambiente</h3>
                <p>
                  Diseño moderno y minimalista con tonos neutros que crean un
                  espacio acogedor y relajante, perfecto para descansar después
                  de un día agitado.
                </p>
              </div>
            </div>
          </div>

          <div className="servicios">
            <h3>Comodidades Incluidas</h3>
            <div className="servicios-grid">
              {servicios.map((s, i) => (
                <div className="servicio-item" key={i}>
                  {s}
                </div>
              ))}
            </div>
          </div>

          <div className="seccion-precio">
            <h3>Desde</h3>
            <div className="precio">$160.000</div>
            <p className="precio-p">Por noche</p>
            <a href="/register">
            <button className="btn">Reservar Ahora</button>
            </a>
          </div>
        </div>
      </div>

      <footer>
        <img className="logo" src="/images/logo.png" alt="Logo Hotel" />
      </footer>
    </div>
  );
};