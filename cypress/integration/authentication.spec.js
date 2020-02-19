describe('Authentication', () => {
    beforeEach(() => {
        cy.exec('php artisan migrate:fresh --env=cypress');
    });

    it('can visit the login page', () => {
        cy.visit('/login');
    });

    it('can log in a user', () => {
        cy.create('User').then(user => {
            cy.visit('/login');
            cy.get('#email').type(user.email);
            cy.get('#password').type('secret');
            cy.get('button').contains('Login').click();
            cy.url().should('include', '/dashboard');
        });
    });

    it('can log out a user', () => {
        cy.login().then(() => {
            cy.visit('/dashboard');
            cy.get('[data-cy=main-menu] .dropdown-toggle-wrap').click();
            cy.get('[data-cy=logout-button]').click();
            cy.visit('/dashboard');
            cy.url().should('include', '/login');
        });
    });
});
