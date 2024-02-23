describe('Test inscription',()=>{
    it('Ajouter un compte', ()=>{
        cy.visit("https://localhost:8000/register");
        cy.get('#registration_form_username').type("Utilisateur")
        cy.get('#registration_form_email').type("noctalyne@laposte.net")
        cy.get('#registration_form_password_first').type("Utilisateur")
        cy.get('#registration_form_password_second').type("Utilisateur")
        cy.get('#registration_form_agreeTerms').click()
        cy.get('#registration_form_submit').click()
    })
    it('Ajouter un compte doublon',()=>{
        cy.visit("https://localhost:8000/register");
        cy.get('#registration_form_username').type("Utilisateur")
        cy.get('#registration_form_email').type("noctalyne@laposte.net")
        cy.get('#registration_form_password_first').type("Utilisateur")
        cy.get('#registration_form_password_second').type("Utilisateur")
        cy.get('#registration_form_agreeTerms').click()
        cy.get('#registration_form_submit').click()
        cy.get('strong').should("contain", "L'adresse noctalyne@laposte.net est déjà utiliser.")
    })
})





