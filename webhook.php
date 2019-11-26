<?php

// https://gist.github.com/boucher/1708172

// SETUP:
// 1. Customize all the settings (stripe api key, email settings, email text)
// 2. Put this code somewhere where it's accessible by a URL on your server.
// 3. Add the URL of that location to the settings at https://manage.stripe.com/#account/webhooks

try {
  // Use Stripe's library to make requests...

  // Include the Stripe library:
  // Assumes you've installed the Stripe PHP library using Composer!
  require_once('vendor/autoload.php');

  // Set your secret key: remember to change this to your live secret key in production
  // See your keys here https://manage.stripe.com/account
  $pk = getenv('STRIPE_PRIVATE_KEY');
  \Stripe\Stripe::setApiKey($pk);

  $support_name  = 'My Support';
  $support_email = 'support@myapp.com';

  // Retrieve the request's body and parse it as JSON
  $body = @file_get_contents('php://input');
  $event_json = json_decode($body);

  // For extra security, retrieve from the Stripe API
  $event_id = $event_json->id;
  $event = \Stripe\Event::retrieve($event_id);

  // https://stripe.com/docs/api/events/types
  $event_type = $event->type;

  switch ($event_type) {
    case "account.updated":
      http_response_code(200);
      break;
    case "account.application.authorized":
      http_response_code(200);
      break;
    case "account.application.deauthorized":
      http_response_code(200);
      break;
    case "account.external_account.created":
      http_response_code(200);
      break;
    case "account.external_account.deleted":
      http_response_code(200);
      break;
    case "account.external_account.updated":
      http_response_code(200);
      break;
    case "application_fee.created":
      http_response_code(200);
      break;
    case "application_fee.refunded":
      http_response_code(200);
      break;
    case "application_fee.refund.updated":
      http_response_code(200);
      break;
    case "balance.available":
      http_response_code(200);
      break;
    case "charge.captured":
      http_response_code(200);
      break;
    case "charge.expired":
      http_response_code(200);
      break;
    case "charge.failed":
      http_response_code(200);
      break;
    case "charge.pending":
      http_response_code(200);
      break;
    case "charge.refunded":
      http_response_code(200);
      break;
    case "charge.succeeded":
      http_response_code(200);
      break;
    case "charge.updated":
      http_response_code(200);
      break;
    case "charge.dispute.closed":
      http_response_code(200);
      break;
    case "charge.dispute.created":
      http_response_code(200);
      break;
    case "charge.dispute.funds_reinstated":
      http_response_code(200);
      break;
    case "charge.dispute.funds_withdrawn":
      http_response_code(200);
      break;
    case "charge.dispute.updated":
      http_response_code(200);
      break;
    case "charge.refund.updated":
      http_response_code(200);
      break;
    case "checkout.session.completed":
      http_response_code(200);
      break;
    case "coupon.created":
      http_response_code(200);
      break;
    case "coupon.deleted":
      http_response_code(200);
      break;
    case "coupon.updated":
      http_response_code(200);
      break;
    case "credit_note.created":
      http_response_code(200);
      break;
    case "credit_note.updated":
      http_response_code(200);
      break;
    case "credit_note.voided":
      http_response_code(200);
      break;
    case "customer.created":
      http_response_code(200);
      break;
    case "customer.deleted":
      http_response_code(200);
      break;
    case "customer.updated":
      http_response_code(200);
      break;
    case "customer.discount.created":
      http_response_code(200);
      break;
    case "customer.discount.deleted":
      http_response_code(200);
      break;
    case "customer.discount.updated":
      http_response_code(200);
      break;
    case "customer.source.created":
      http_response_code(200);
      break;
    case "customer.source.deleted":
      http_response_code(200);
      break;
    case "customer.source.expiring":
      http_response_code(200);
      break;
    case "customer.source.updated":
      http_response_code(200);
      break;
    case "customer.subscription.created":
      http_response_code(200);
      break;
    case "customer.subscription.deleted":
      http_response_code(200);
      break;
    case "customer.subscription.trial_will_end":
      http_response_code(200);
      break;
    case "customer.subscription.updated":
      http_response_code(200);
      break;
    case "customer.tax_id.created":
      http_response_code(200);
      break;
    case "customer.tax_id.deleted":
      http_response_code(200);
      break;
    case "customer.tax_id.updated":
      http_response_code(200);
      break;
    case "file.created":
      http_response_code(200);
      break;
    case "invoice.created":
      http_response_code(200);
      break;
    case "invoice.deleted":
      http_response_code(200);
      break;
    case "invoice.finalized":
      http_response_code(200);
      break;
    case "invoice.marked_uncollectible":
      http_response_code(200);
      break;
    case "invoice.payment_action_required":
      http_response_code(200);
      break;
    case "invoice.payment_failed":
      http_response_code(200);
      break;
    case "invoice.payment_succeeded":
      // This will send receipts on succesful invoices
      email_invoice_receipt($event->data->object);
      break;
    case "invoice.sent":
      http_response_code(200);
      break;
    case "invoice.upcoming":
      http_response_code(200);
      break;
    case "invoice.updated":
      http_response_code(200);
      break;
    case "invoice.voided":
      http_response_code(200);
      break;
    case "invoiceitem.created":
      http_response_code(200);
      break;
    case "invoiceitem.deleted":
      http_response_code(200);
      break;
    case "invoiceitem.updated":
      http_response_code(200);
      break;
    case "issuing_authorization.created":
      http_response_code(200);
      break;
    case "issuing_authorization.request":
      http_response_code(200);
      break;
    case "issuing_authorization.updated":
      http_response_code(200);
      break;
    case "issuing_card.created":
      http_response_code(200);
      break;
    case "issuing_card.updated":
      http_response_code(200);
      break;
    case "issuing_cardholder.created":
      http_response_code(200);
      break;
    case "issuing_cardholder.updated":
      http_response_code(200);
      break;
    case "issuing_dispute.created":
      http_response_code(200);
      break;
    case "issuing_dispute.updated":
      http_response_code(200);
      break;
    case "issuing_settlement.created":
      http_response_code(200);
      break;
    case "issuing_settlement.updated":
      http_response_code(200);
      break;
    case "issuing_transaction.created":
      http_response_code(200);
      break;
    case "issuing_transaction.updated":
      http_response_code(200);
      break;
    case "order.created":
      http_response_code(200);
      break;
    case "order.payment_failed":
      http_response_code(200);
      break;
    case "order.payment_succeeded":
      http_response_code(200);
      break;
    case "order.updated":
      http_response_code(200);
      break;
    case "order_return.created":
      http_response_code(200);
      break;
    case "payment_intent.amount_capturable_updated":
      http_response_code(200);
      break;
    case "payment_intent.created":
      http_response_code(200);
      break;
    case "payment_intent.payment_failed":
      http_response_code(200);
      break;
    case "payment_intent.succeeded":
      http_response_code(200);
      break;
    case "payment_method.attached":
      http_response_code(200);
      break;
    case "payment_method.card_automatically_updated":
      http_response_code(200);
      break;
    case "payment_method.detached":
      http_response_code(200);
      break;
    case "payout.canceled":
      http_response_code(200);
      break;
    case "payout.created":
      http_response_code(200);
      break;
    case "payout.failed":
      http_response_code(200);
      break;
    case "payout.paid":
      http_response_code(200);
      break;
    case "payout.updated":
      http_response_code(200);
      break;
    case "person.created":
      http_response_code(200);
      break;
    case "person.deleted":
      http_response_code(200);
      break;
    case "person.updated":
      http_response_code(200);
      break;
    case "plan.created":
      http_response_code(200);
      break;
    case "plan.deleted":
      http_response_code(200);
      break;
    case "plan.updated":
      http_response_code(200);
      break;
    case "product.created":
      http_response_code(200);
      break;
    case "product.deleted":
      http_response_code(200);
      break;
    case "product.updated":
      http_response_code(200);
      break;
    case "recipient.created":
      http_response_code(200);
      break;
    case "recipient.deleted":
      http_response_code(200);
      break;
    case "recipient.updated":
      http_response_code(200);
      break;
    case "reporting.report_run.failed":
      http_response_code(200);
      break;
    case "reporting.report_run.succeeded":
      http_response_code(200);
      break;
    case "reporting.report_type.updated":
      http_response_code(200);
      break;
    case "review.closed":
      http_response_code(200);
      break;
    case "review.opened":
      http_response_code(200);
      break;
    case "sigma.scheduled_query_run.created":
      http_response_code(200);
      break;
    case "sku.created":
      http_response_code(200);
      break;
    case "sku.deleted":
      http_response_code(200);
      break;
    case "sku.updated":
      http_response_code(200);
      break;
    case "source.canceled":
      http_response_code(200);
      break;
    case "source.chargeable":
      http_response_code(200);
      break;
    case "source.failed":
      http_response_code(200);
      break;
    case "source.mandate_notification":
      http_response_code(200);
      break;
    case "source.refund_attributes_required":
      http_response_code(200);
      break;
    case "source.transaction.created":
      http_response_code(200);
      break;
    case "source.transaction.updated":
      http_response_code(200);
      break;
    case "tax_rate.created":
      http_response_code(200);
      break;
    case "tax_rate.updated":
      http_response_code(200);
      break;
    case "topup.canceled":
      http_response_code(200);
      break;
    case "topup.created":
      http_response_code(200);
      break;
    case "topup.failed":
      http_response_code(200);
      break;
    case "topup.reversed":
      http_response_code(200);
      break;
    case "topup.succeeded":
      http_response_code(200);
      break;
    case "transfer.created":
      http_response_code(200);
      break;
    case "transfer.failed":
      http_response_code(200);
      break;
    case "transfer.paid":
      http_response_code(200);
      break;
    case "transfer.reversed":
      http_response_code(200);
      break;
    case "transfer.updated":
      http_response_code(200);
      break;

    default:
      // Not implemented
      http_response_code(501);
  }
} catch(\Stripe\Error\Card $e) {
  // Since it's a decline, \Stripe\Error\Card will be caught
  $body = $e->getJsonBody();
  $err  = $body['error'];

  print('Status is:' . $e->getHttpStatus() . "\n");
  print('Type is:' . $err['type'] . "\n");
  print('Code is:' . $err['code'] . "\n");
  // param is '' in this case
  print('Param is:' . $err['param'] . "\n");
  print('Message is:' . $err['message'] . "\n");
} catch (\Stripe\Error\RateLimit $e) {
  // Too many requests made to the API too quickly
} catch (\Stripe\Error\InvalidRequest $e) {
  // Invalid parameters were supplied to Stripe's API
} catch (\Stripe\Error\Authentication $e) {
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)
} catch (\Stripe\Error\ApiConnection $e) {
  // Network communication with Stripe failed
} catch (\Stripe\Error\Base $e) {
  // Display a very generic error to the user, and maybe send
  // yourself an email
} catch (Exception $e) {
  // Something else happened, completely unrelated to Stripe
}

function email_invoice_receipt($invoice) {
  $customer = \Stripe\Customer::retrieve($invoice->customer);

  //Make sure to customize your from address
  $subject = 'Your payment has been received';
  $headers = 'From: "' . $support_name . '" <' . $support_email . '>';

  //mail($customer->email, $subject, message_body(), $headers);
  http_response_code(200);
}

function format_stripe_amount($amount) {
  return sprintf('$%0.2f', $amount / 100.0);
}

function format_stripe_timestamp($timestamp) {
  return strftime("%m/%d/%Y", $timestamp);
}

function message_body($invoice, $customer) {
  $subscription = $invoice->lines->subscriptions[0];
  return <<<'EOF'
Dear {$customer->email}:

This is a receipt for your subscription. This is only a receipt, 
no payment is due. Thanks for your continued support!

-------------------------------------------------
SUBSCRIPTION RECEIPT

Email: {$customer->email}
Plan: {$subscription->plan->name}
Amount: {format_stripe_amount($invoice->total)} (USD)

For service between {format_stripe_timestamp($subscription->period->start)} and {format_stripe_timestamp($subscription->period->end)}

-------------------------------------------------

EOF;
}

?>