import React from "react";

const servicios = [
  {
    icono: "/images/icono-hotel.png",
    titulo: "Hotel",
    descripcion:
      "Contamos con habitaciones sencillas, con bañera, con jacuzzi, dobles, triples y multiples.",
  },
  {
    icono: "/images/icono-servicio.png",
    titulo: "Servicios",
    descripcion:
      "Ofrecemos wifi gratuito en todas las areas del hotel, parqueadero y un minibar equipado para tu comodidad durante toda tu estadia.",
  },
];

export const Servicios = () => {
  return (
    <section className="servicios-container">
      <div className="servicios-grid">
        {servicios.map((servicio, index) => (
          <div className="servicio-card" key={index}>
            <img className="servicio-icon" src={servicio.icono} alt={servicio.titulo} />
            <h3>
              <span>{servicio.titulo}</span>
            </h3>
            <p>{servicio.descripcion}</p>
          </div>
        ))}
      </div>
    </section>
  );
};

