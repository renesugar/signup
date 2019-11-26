<?php

// http://www.larryullman.com/series/processing-payments-with-stripe/
// https://stripe.com/docs/billing/webhooks
// https://stripe.com/docs/billing/testing
// https://stripe.com/docs/billing/lifecycle
// https://stripe.com/docs/billing/subscriptions/payment#handling-action-required
// https://stripe.com/docs/api/events

$strSubscriptions = file_get_contents("subscriptions.json");
$arraySubscriptions = json_decode($strSubscriptions, true);
//var_dump($arraySubscriptions);

// Check for a form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Stores errors:
    $errors = array();

    // Need a payment token:
    if (isset($_POST['stripeToken'])) {

        $token = $_POST['stripeToken'];

        // Check for a duplicate submission, just in case:
        // Uses sessions, you could use a cookie instead.
        if (isset($_COOKIE['token']) && ($_COOKIE['token'] == $token)) {
            $errors['token'] = 'You have submitted the form again.';
            echo 'You have submitted the form again.<br/>';
        } else {
            // New submission
            setcookie("token", $token, time() + 1800);
        }
    } else {
        print '<pre>' . print_r($_POST, true) . '</pre>';
        $errors['token'] = 'The order cannot be processed. Enable JavaScript and try again.';
        echo 'The order cannot be processed. Enable JavaScript and try again.<br/>';
    }

    // Set the order amount

    $plan = $_POST['f1-plan'];

    // Set Stripe plan identifiers

    $stripePlan = "";

    if (isset($plan)) {
        if ($plan == "bronze") {
            $stripePlan = $arraySubscriptions[$plan];
        } elseif ($plan == "silver") {
            $stripePlan = $arraySubscriptions[$plan];
        } elseif ($plan == "gold") {
            $stripePlan = $arraySubscriptions[$plan];
        } else {
            $errors['plan'] = 'Unrecognized plan.';
            echo "Unrecognized plan.<br/>";
        }
    } else {
        $errors['plan'] = 'The order cannot be processed. Select a subscription plan and try again.';
        echo 'The order cannot be processed. Select a subscription plan and try again.<br/>';
    }

    // Validate other form data

    // If no errors, process the order:
    if (empty($errors)) {
        // Create the charge on Stripe's servers - this will charge the user's card
        try {

            // Include the Stripe library:
            // Assumes you've installed the Stripe PHP library using Composer!
            require_once('vendor/autoload.php');

            // Set your secret key: remember to change this to your live secret key in production
            // see your keys here https://manage.stripe.com/account

            $pk = getenv('STRIPE_PRIVATE_KEY');
            \Stripe\Stripe::setApiKey($pk);

            // Create Stripe customer

            $password = $_POST['f1-password'];
            $repeat_password = $_POST['f1-repeat-password'];

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Double check that user typed in the same password twice

            if (password_verify($repeat_password, $password_hash)) {
                echo "Password is valid.<br/>\n";
            } else {
                echo "Invalid password.<br/>\n";
                echo "Password: \"" . $password . "\"<br/>\n";
                echo "Repeat Password: \"" . $repeat_password . "\"<br/>\n";
                return;
            }

            // Sanitize posted data

            $first_name = strip_tags(trim($_POST['f1-first-name']));
            $middle_name = strip_tags(trim($_POST['f1-middle-name']));
            $last_name = strip_tags(trim($_POST['f1-last-name']));
            $mobile_number = strip_tags(trim($_POST['f1-mobile-number']));
            $home_number = strip_tags(trim($_POST['f1-home-number']));
            $work_number = strip_tags(trim($_POST['f1-work-number']));
            $plan = strip_tags(trim($_POST['f1-plan']));

            $email = strip_tags(trim($_POST['f1-email']));
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            $payment_email = strip_tags(trim($_POST['payment-email']));
            $payment_email = filter_var($payment_email, FILTER_VALIDATE_EMAIL);

            $payment_phone = strip_tags(trim($_POST['payment-phone']));

            $metadata = [
                "first_name" => $first_name,
                "middle_name" => $middle_name,
                "last_name" => $last_name,
                "mobile_number" => $mobile_number,
                "home_number" => $home_number,
                "work_number" => $work_number,
                "plan" => $plan,
                "email" => $email
            ];

            // Check if customer with the same email address already exists
            list($email_addresses, $err) = getCustomersByEmailAddress($payment_email);

            if (count($email_addresses) > 0) {
                echo "Customer " . $payment_email . " already exists.<br/>\n";
                return;
            }

            if ($err != null) {
                echo "An error occured looking up customer by email address.<br/>\n";
                return;
            }

            echo 'Create new customer<br/>';

            $customer = \Stripe\Customer::create([
                "name" => $first_name . " " . $middle_name . " " . $last_name,
                "email" => $payment_email,
                "phone" => $payment_phone,
                "metadata" => $metadata,
                "source" => $token,
                "description" => "Customer for " . $email
            ]);
            echo "<pre>=====================\n";
            echo $customer;
            echo "\n=====================</pre>";

            $customer_id = $customer->id;

            echo "Create new subscription plan='" . $stripePlan . "' for customer id='" . $customer_id . "'<br/>\n";

            // Create the subscription:
            $subscription = \Stripe\Subscription::create([
                "customer" => $customer_id,
                "items" => [
                  [
                    "plan" => $stripePlan,
                  ],
                ]
            ]);

            echo 'Check subscription status<br/>';

            // Check that it was paid:
            if (($subscription->status == "active") || ($subscription->status == "trialing")) {
                // Create new user in application's database
                echo 'Create new user<br/>';
                // Send the signup email
                echo 'Send the signup email<br/>';

                print '<pre>' . print_r($_POST, true) . '</pre>';
            } else { // Transaction rejected
                echo 'You have not been charged because the payment system rejected the transaction.<br/>You can try again or use another card.<br/>';
            }

        } catch (\Stripe\Error\Card $e) {
            // Card was declined
            echo 'Card was declined<br/>';

            $e_json = $e->getJsonBody();
            $err = $e_json['error'];
            $errors['stripe'] = $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network problem (retry?)
            echo 'Network problem<br/>';
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            echo 'Too many requests<br/>';
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid request
            echo 'Invalid request<br/>';
        } catch (\Stripe\Error\Api $e) {
            // Stripe's servers are down
            echo "Stripe's servers are down<br/>";
        } catch (\Stripe\Error\Base $e) {
            // Other error
            echo 'Other error<br/>';
        } catch (Exception $e) {
            // Other error unrelated to Stripe
            echo 'Other non-payment error<br/>';
        }
    } // A user form submission error occurred, handled below.
} // Form submission.

/**
 * NOTE: Stripe allows multiple customers to have the same email address.
 * @see https://stripe.com/docs/api/php#list_customers
 * @see https://stackoverflow.com/a/38492724/470749
 * @param string $emailAddress
 * @return array
 */
function getCustomersByEmailAddress($emailAddress) {
    try {
        $response = \Stripe\Customer::all(["limit" => 100, "email" => $emailAddress]);
        return array($response->data, null);
    } catch (\Exception $e) {
        return array([], $e);
    }
}

?>

<?php // Show PHP errors, if they exist:
    if (isset($errors) && !empty($errors) && is_array($errors)) {
        echo 'The following error(s) occurred:<ul>';
        foreach ($errors as $e) {
            echo "<li>$e</li>";
        }
        echo '</ul>';
    }
?>