describe('Filters', () => {
    before(() => {
        cy.exec('php artisan db:seed --class=AssignedPartsSeeder --env=cypress');
        cy.exec('php artisan db:seed --class=UserSetsSeeder --env=cypress');
    });

    beforeEach(() => {
        cy.login();
    });
    
    context('User Parts', () => {
        it('can filter using a name value', () => {
            cy.visit('/legouser/parts/all');
    
            cy.get('.card-container .card').should('have.length', 6);
            cy.get('[data-cy=form-filters]').should('not.be.visible');
            cy.get('[data-cy=filters-show-button]').click();
            cy.get('[data-cy=form-filters]').should('be.visible').find('input').should('have.length', 3);

            cy.get('#filter_name').type('brick');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 4);

            cy.get('#filter_name').type(', 2 x');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 3);
        });

        it('can filter using a cateogory value', () => {
            cy.visit('/legouser/parts/all');
    
            cy.get('[data-cy=filters-show-button]').click();

            cy.get('#filter_category_label').type('bricks s');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 4);

            cy.get('#filter_category_label').clear().type('bricks, slo');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 2);
        });

        it('can filter using a part num value', () => {
            cy.visit('/legouser/parts/all');
    
            cy.get('[data-cy=filters-show-button]').click();

            cy.get('#filter_part_num').type('30');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 4);

            cy.get('#filter_part_num').clear().type('30, 00');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 2);
        });

        it('can use multiple filter values', () => {
            cy.visit('/legouser/parts/all');
    
            cy.get('[data-cy=filters-show-button]').click();
            
            cy.get('#filter_name').type('2 x');
            cy.get('#filter_part_num').type('30');
            cy.get('#filter_category_label').type('sloped');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 2);
        });
    });

    context('User Sets', () => {
        it('can filter using a name value', () => {
            cy.visit('/legouser/sets-grid');
    
            cy.get('.card-container .card').should('have.length', 7);
            cy.get('[data-cy=form-filters]').should('not.be.visible');
            cy.get('[data-cy=filters-show-button]').click();
            cy.get('[data-cy=form-filters]').should('be.visible').find('input').should('have.length', 6);

            cy.get('#filter_name').type('republic');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 2);

            cy.get('#filter_name').type(', gun');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 1);
        });

        it('can filter using a set number value', () => {
            cy.visit('/legouser/sets-grid');
    
            cy.get('[data-cy=filters-show-button]').click();
            
            cy.get('#filter_set_num').type('80');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 2);

            cy.get('#filter_set_num').type(', 69');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 1);
        });

        it('can filter using a theme value', () => {
            cy.visit('/legouser/sets-grid');
    
            cy.get('[data-cy=filters-show-button]').click();

            cy.get('#filter_theme_label').type('star wars');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 4);

            cy.get('#filter_theme_label').type(', collector');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 2);
        });

        it('can filter using a year value', () => {
            cy.visit('/legouser/sets-grid');
    
            cy.get('[data-cy=filters-show-button]').click();
            
            cy.get('#filter_year').type('2011');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 3);

            cy.get('#filter_year').clear().type('-2010');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 3);

            cy.get('#filter_year').clear().type('2011-');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 4);

            cy.get('#filter_year').clear().type('2010-2011');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 5);
        });

        it('can filter using piece count values', () => {
            cy.visit('/legouser/sets-grid');
    
            cy.get('[data-cy=filters-show-button]').click();
            
            cy.get('#filter_minpieces').type('1500');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 3);

            cy.get('#filter_minpieces').clear();
            cy.get('#filter_maxpieces').type('1200');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 2);

            cy.get('#filter_minpieces').type('1000');
            cy.get('#filter_maxpieces').clear().type('1800');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 4);
        });

        it('can use multiple filter values', () => {
            cy.visit('/legouser/sets-grid');
    
            cy.get('[data-cy=filters-show-button]').click();
            
            cy.get('#filter_name').type('i');
            cy.get('#filter_set_num').type('10');
            cy.get('#filter_theme_label').type('star wars');
            cy.get('#filter_year').clear().type('2010-');
            cy.get('#filter_minpieces').type('1500');
            cy.get('[data-cy=filters-apply-button]').click();
            cy.get('.card-container .card').should('have.length', 1);
        });
    });
});
