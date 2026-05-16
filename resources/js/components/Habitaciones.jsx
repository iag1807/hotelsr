import React from "react";
import { Link } from "react-router-dom";

const habitaciones = [
  {
    nombre: "Sencilla",
    imagen: "/images/sencilla.jpeg",
    detalles: ["Cama semidoble, baño privado, televisor", "Internet gratuito"],
    link: "/habitaciones/sencilla",
  },
  {
    nombre: "Con bañera",
    imagen: "/images/bañera.jpeg",
    detalles: [
      "Cama semidoble, baño privado, bañera, televisor",
      "Internet gratuito",
    ],
    link: "/habitaciones/bañera",
  },
  {
    nombre: "Con jacuzzi",
    imagen: "/images/jacuzzi.jpeg",
    detalles: [
      "Cama semidoble, baño privado, jacuzzi, televisor",
      "Internet gratuito",
    ],
    link: "/habitaciones/jacuzzi",
  },
  {
    nombre: "Doble",
    imagen: "/images/doble.jpeg",
    detalles: [
      "Dos camas semidobles, baño privado, televisor",
      "Internet gratuito",
    ],
    link: "/habitaciones/doble",
  },
  {
    nombre: "Triple",
    imagen: "/images/triple.jpeg",
    detalles: [
      "Una cama semidoble, un camarote, baño privado, televisor",
      "Internet gratuito",
    ],
    link: "/habitaciones/triple",
  },
  {
    nombre: "Multiple",
    imagen: "/images/multiple.jpeg",
    detalles: [
      "Dos camarotes, una cama de un metro, baño privado, televisor",
      "Internet gratuito",
    ],
    link: "/habitaciones/multiple",
  },
];

export const Habitaciones = () => {
  return (
    <>
      <div id="habitaciones"></div>
      <section className="habitaciones-section">
        <div className="section-header">
          <p className="section-subtitle">Conoce</p>
          <h1 className="section-title">
            NUESTRAS <span>HABITACIONES</span>
          </h1>
        </div>

        <div className="habitaciones-grid">
          {habitaciones.map((hab, index) => (
            <div className="habitacion-card" key={index}>
              <img
                className="habitacion-imagen"
                src={hab.imagen}
                alt={hab.nombre}
              />
              <div className="habitacion-content">
                <h3 className="habitacion-nombre">{hab.nombre}</h3>
                {hab.detalles.map((detalle, i) => (
                  <p className="habitacion-detalles" key={i}>
                    {detalle}
                  </p>
                ))}
                <Link to={hab.link} className="btn-reservar">
                  Ver detalles
                </Link>
              </div>
            </div>
          ))}
        </div>
      </section>
    </>
  );
};