<!DOCTYPE html>
<html lang="en">

<head>
    <title>Product Landing Page</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <nav class="navbar">
            <a class="nav-item" href="#">Home</a>
            <a class="nav-item" href="#">Features</a>
            <a class="nav-item" href="#">Pricing</a>
            <a class="nav-item" href="#">Contact</a>
        </nav>
    </header>
    <main class="main">
        <h1 class="title">Product Name</h1>
        <p class="description">Product Description</p>
        <div class="product">
            <img src="product.png" alt="Product Image">
        </div>
        <button class="cta-btn">Buy Now</button>
    </main>
    <form action="checkout.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br>
        <label for="card-element">
            Credit or debit card
        </label>
        <div id="card-element">
            <!-- A Stripe Element will be inserted here. -->
        </div>

        <!-- Used to display form errors. -->
        <div id="card-errors" role="alert"></div>
        <button type="submit">Submit Payment</button>
    </form>

    <!-- Stripe JavaScript library -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Set your publishable key
        const stripe = Stripe('YOUR_PUBLISHABLE_KEY');

        // Create a Stripe client
        const elements = stripe.elements();

        // Set up Stripe.js and Elements to use in checkout form
        const style = {
            base: {
                color: "#32325d",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#aab7c4"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        const card = elements.create("card", {
            style: style
        });
        card.mount("#card-element");

        card.on("change", function(event) {
            // Disable the Pay button if there are no card details in the Element
            document.querySelector("button").disabled = event.empty;
            document.querySelector("#card-errors").textContent = event.error ? event.error.message : "";
        });

        const form = document.getElementById("payment-form");
        form.addEventListener("submit", function(event) {
            event.preventDefault();
            // Complete payment when the submit button is clicked
            payWithCard(stripe, card);
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            const form = document.getElementById("payment-form");
            const hiddenInput = document.createElement("input");
            hiddenInput.setAttribute("type", "hidden");
            hiddenInput.setAttribute("name", "stripeToken");
            hiddenInput.setAttribute("value", token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }

        // Create a token and charge the card
        function payWithCard(stripe, card) {
            stripe
                .createToken(card)
                .then((result) => {
                    if (result.error) {
                        // Inform the user if there was an error
                        document.querySelector("#card-errors").textContent = result.error.message;
                    } else {
                        // Send the token to your server
                        stripeTokenHandler(result.token);
                    }
                });
        }
    </script>
</body>

</html>