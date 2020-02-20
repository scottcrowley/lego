describe('Storage - Unassigned Parts', () => {
    before(() => {
        // cy.exec('php artisan migrate:fresh --env=cypress');
        cy.exec('php artisan db:seed --class=UnassignedPartsSeeder --env=cypress');
        cy.exec('php artisan db:seed --class=StorageLocationsSeeder --env=cypress');
    });

    beforeEach(() => {
        cy.login();
    });

    it('can display all unassigned parts', () => {
        cy.visit('/storage/locations/parts/unassigned');

        cy.get('.card-container .card').should('have.length', 6);
    });

    it('can select parts to move', () => {
        cy.visit('/storage/locations/parts/unassigned');

        cy.get('.card-container .card:nth-child(4) .card-content [data-cy=select-part-button]')
            .dblclick()
            .parent('.card-content')
            .should('not.have.class', 'border-primary');

        cy.get('.card-container .card:nth-child(5) .card-content [data-cy=select-part-button]')
            .click()
            .parent('.card-content')
            .should('have.class', 'border-primary');
        
        cy.get('.card-container .card:nth-child(6) .card-content [data-cy=select-part-button]')
            .click();
        
        cy.get('.card-container .card .card-content.border-primary').should('have.length', 2);
    });

    it('can select a storage location and move parts', () => {
        cy.visit('/storage/locations/parts/unassigned');

        cy.get('[data-cy=button-move-selected]').should('be.disabled');
        cy.get('[data-cy=button-move-all]').should('be.disabled');

        cy.get('#moveToLocation').select('51');
        cy.get('[data-cy=button-move-selected]').should('be.disabled');
        cy.get('[data-cy=button-move-all]').should('not.be.disabled');

        cy.get('.card-container .card:nth-child(5) .card-content [data-cy=select-part-button]').click();
        cy.get('[data-cy=button-move-selected]').should('not.be.disabled');
        cy.get('.card-container .card:nth-child(6) .card-content [data-cy=select-part-button]').click();
        
        cy.get('[data-cy=button-move-selected]').click();
        cy.get('.card-container .card').should('have.length', 4);

        cy.visit('/storage/locations/51/parts');
        cy.get('.card-container .card').should('have.length', 2);
    });
});
