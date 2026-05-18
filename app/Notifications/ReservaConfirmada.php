<?php

namespace App\Notifications;

use App\Models\Reserva;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservaConfirmada extends Notification
{
    public function __construct(private Reserva $reserva) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $fecha_ingreso = $this->reserva->fecha_ingreso->format('d \d\e F \d\e Y');
        $fecha_salida = $this->reserva->fecha_salida->format('d \d\e F \d\e Y');

        return (new MailMessage)
            ->subject('Tu reserva ha sido confirmada - Hotel Sueño Real')
            ->greeting($notifiable->saludo() . ', ' . $notifiable->name . '!')
            ->line('Nos complace informarte que tu reserva ha sido confirmada correctamente.')
            ->line('**Detalles de tu reserva:**')
            ->line('Habitación: ' . $this->reserva->habitacion->numero . ' · ' . ucfirst($this->reserva->habitacion->tipo_habitacion))
            ->line('Fecha de ingreso: ' . $fecha_ingreso)
            ->line('Fecha de salida: ' . $fecha_salida)
            ->line('Número de personas: ' . $this->reserva->numero_personas)
            ->line('Total a pagar: $' . number_format($this->reserva->total, 2, ',', '.'))
            ->line('Pago inicial realizado: $' . number_format($this->reserva->pago_anticipado, 2, ',', '.'))
            ->line('Saldo pendiente: $' . number_format($this->reserva->saldoPendiente(), 2, ',', '.'))
            ->line('---')
            ->line('Por favor, completa el pago del saldo pendiente antes de tu fecha de ingreso.')
            ->action('Ver mi reserva', route('cliente.reservas'))
            ->line('Si tienes alguna pregunta, no dudes en contactarnos.')
            ->salutation('¡Te esperamos en Hotel Sueño Real!');
    }
}
