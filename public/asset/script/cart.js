// // Déclaration d'une variable pour stocker les produits dans le panier
// var panier = [];


// // Fonction pour ajouter un produit au panier
// function ajouterAuPanier(produit) {
//     panier.push(produit);
//     sessionStorage.setItem(panier, panier);
//     console.log("Produit ajouté au panier :", produit);
// }

// // Fonction pour supprimer un produit du panier
// function supprimerDuPanier(index) {
//     if (index > -1) {
//         panier.splice(index, 1);
//         console.log("Produit supprimé du panier");
//     }
// }

// // Fonction pour afficher le contenu du panier
// function afficherPanier() {
//     console.log("Contenu du panier :" + panier);
//     panier.forEach(function(produit, index) {
//         console.log(index + 1 + ". " + produit.nom + " - Prix : " + produit.prix + " EUR");
//     });
// }

// console.log(panier);

// afficherPanier();

$(document).ready(function() {
    // Écouter le clic sur le bouton "Ajouter au panier"
    $('.ajouter-au-panier').click(function() {
        var nom = $(this).data('nom');
        var prix = $(this).data('prix');
        ajouterAuPanier(nom, prix);
    });

    // Fonction pour ajouter un produit au panier côté client
    function ajouterAuPanier(nom, prix) {
        var produit = { nom: nom, prix: prix };

        // Envoi des données du produit au serveur via une requête AJAX
        $.ajax({
            type: 'POST',
            url: './_ajouter_produit_panier.html.twig', // Chemin vers votre action Symfony pour ajouter au panier
            data: JSON.stringify(produit),
            contentType: 'application/json',
            success: function() {
                // Actualiser l'affichage du panier après avoir ajouté un produit
                actualiserPanier();
            }
        });
    }
    // Fonction pour actualiser l'affichage du panier côté client
    function actualiserPanier() {
        $.ajax({
            type: 'GET',
            url: '/panier/show.html.twig', // Chemin vers votre action Symfony pour afficher le panier
            success: function(data) {
                // Afficher le contenu du panier
                $('#panier').html(data);
            }
        });
    }
});