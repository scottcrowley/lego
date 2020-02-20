describe('Storage - Assigned Parts', () => {
    before(() => {
        cy.exec('php artisan db:seed --class=AssignedPartsSeeder --env=cypress');
    });

    beforeEach(() => {
        cy.login();
    });

    it('can display all assigned parts for a location', () => {
        cy.visit('/storage/locations/51/parts');

        cy.get('.card-container .card').should('have.length', 2);
    });

    it('can display all assigned individual parts for a location', () => {
        cy.visit('/storage/locations/51/parts');
        cy.get('[data-cy=individual-parts-button]').click();
        cy.url().should('include', '51/parts/individual');

        cy.get('.card-container .card').should('have.length', 4);
    });

    it('can remove and add a part to a location', () => {
        cy.visit('/storage/locations/51/parts');

        cy.get('.card-container .card:nth-child(2) .card-body button').contains('Remove Part').click();
        cy.get('.card-container .card').should('have.length', 1);

        cy.get('[data-cy=edit-parts-button]').click();
        cy.url().should('include', '51/parts/edit');

        cy.get('.card-container .card').should('have.length', 6);
        cy.get('.card-container .card-body button').contains('Remove Part').should('have.length', 1);
        cy.get('.card-container .card-body button').contains('Add Part')
            .should('have.length', 1)
            .click();

        cy.get('[data-cy=view-parts-button]').click();
        cy.url().should('include', '51/parts?');
        cy.get('.card-container .card').should('have.length', 2);
    });

    it('can move parts to a new location', () => {
        cy.visit('/storage/locations/51/parts');

        cy.get('[data-cy=move-parts-button]').click();
        cy.url().should('include', '51/parts/move');
        cy.get('.card-container .card').should('have.length', 2);

        cy.get('[data-cy=button-move-selected]').should('be.disabled');
        cy.get('[data-cy=button-move-all]').should('be.disabled');

        cy.get('#moveToLocation').select('152');
        cy.get('[data-cy=button-move-selected]').should('be.disabled');
        cy.get('[data-cy=button-move-all]').should('not.be.disabled');

        cy.get('.card-container .card:nth-child(1) .card-content [data-cy=select-part-button]').click();
        cy.get('[data-cy=button-move-selected]').should('not.be.disabled');
        
        cy.get('[data-cy=button-move-selected]').click();
        cy.get('.card-container .card').should('have.length', 1);

        cy.visit('/storage/locations/152/parts');
        cy.get('.card-container .card').should('have.length', 2);
    });

    it('can move all parts to a new location', () => {
        cy.visit('/storage/locations/152/parts/move');

        cy.get('#moveToLocation').select('51');
        cy.get('[data-cy=button-move-all]').click();
        
        cy.get('.card-container .card').should('have.length', 0);

        cy.visit('/storage/locations/51/parts');
        cy.get('.card-container .card').should('have.length', 3);
    });
});
