describe('Login', () => {
    it('can visit the login page', () => {
        cy.visit('/login');
    });

    it('can log in a user', () => {
        cy.create('User').then(user => {
            cy.visit('/login')
            cy.get('#email').type(user.email)
            cy.get('#password').type('secret')
            cy.get('button').contains('Login').click();
            cy.url().should('include', '/dashboard');
        });
    });
});
