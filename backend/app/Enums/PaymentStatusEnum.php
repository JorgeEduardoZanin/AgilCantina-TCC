<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case PAID = 'paid'; // Pagamento aprovado
    case PENDING = 'pending'; // Pagamento em processamento
    case NOT_PAID = 'not_paid'; // Pagamento recusado
}