// const urlParams = new URLSearchParams(window.location.search);
// var userId = urlParams.get('user_id');
// var produitId = urlParams.get('id');


// $(document).ready(function () {
//     // Écouter le clic sur le bouton "Ajouter au panier"
//     $('.ajouter-au-panier').click(function () {
//         // var produitId = $(this).data('id');
//         // var user = $(this).data('user'.id);
//         var user = $(this).data('user');
//         var nom = $(this).data('nom');
//         var prix = $(this).data('prix');
//         var id = $(this).data('id');
//         ajouterAuPanier(user, id, nom, prix);
//     });

//     // Fonction pour ajouter un produit au panier côté client
//     function ajouterAuPanier(user, id, nom, prix) {
//         var user = user;
        
//         var produit = { nom: nom, prix: prix };

//         // Envoi des données du produit au serveur via une requête AJAX
//         $.ajax({
//             type: 'POST',
//             url: '/produits/' + id + '/add', // Chemin vers votre action Symfony pour ajouter au panier
//             data: JSON.stringify(produit),
//             contentType: 'application/json',
//             success: function () {
//                 // Actualiser l'affichage du panier après avoir ajouté un produit
//                 actualiserPanier(user, id);
//                 console.log(produit);
//             }
//         });
        
//     }
//     // Fonction pour actualiser l'affichage du panier côté client
//     function actualiserPanier(user, id) {
//         $.ajax({
//             type: 'GET',
//             // url: '/panier/show/cart/'+ user_id , // Chemin vers votre action Symfony pour afficher le panier
//             url: '/panier/show/cart/' + user,
//             success: function (data) {
//                 // Afficher le contenu du panier
//                 $('#panier').html(data);
//             }
//         });
//     }
// });


