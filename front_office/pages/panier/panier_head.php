<title>Formation IFR - Votre Panier</title>
<script>
    // Fonction Javascript pour la gestion des modifications des quantité dans le panier

    document.addEventListener('DOMContentLoaded', function() {
        const productInputs = document.querySelectorAll('.qty');

        function handleProductInputBlur(event) {
            const input = event.target;
            const value = input.value;
            const attrValue = input.getAttribute('attr');

            // Fais quelque chose avec les valeurs récupérées (par exemple, redirection).
            if(value == 0) {
                window.location.href = `index.php?page=fo_panier&del_id_produit=${attrValue}`;
            }else {
                window.location.href = `index.php?page=fo_panier&update_panier=1&id_produit=${attrValue}&new_qte=${encodeURIComponent(value)}`;
            }
        }

        productInputs.forEach(input => {
            input.addEventListener('blur', handleProductInputBlur);
        });
    });
</script>
<link rel="stylesheet" type="text/css" href="front_office/css/shop.css"/>