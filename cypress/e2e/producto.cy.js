/// <reference types="cypress"/>

describe('Ingresando a la página principal', () => {
  it('Ingresando a la página principal', () => {

    cy.visit('/');
    cy.get('[data-cy="login-nickname"]').should('exist');
    cy.get('[data-cy="login-nickname"]').focus();
    cy.get('[data-cy="login-nickname"]').type('bfandino');
    
    cy.get('[data-cy="login-password"]').should('exist');
    cy.get('[data-cy="login-password"]').focus();
    cy.get('[data-cy="login-password"]').type('Asdfg123*');
    
    cy.get('[data-cy="form-login"]').submit();
    // cy.wait(500);
    
    cy.get('[data-cy="boton-menu"]').should('exist');
    cy.get('[data-cy="boton-menu"]').click();
    // cy.wait(250);
    
    cy.get('[data-cy="articulos"]').should('exist');
    cy.get('[data-cy="articulos"]').click();
    
    cy.get('[data-cy="producto"]').should('exist');
    cy.get('[data-cy="producto"]').click();
    
    // Llenando el formulario de producto
    cy.wait(500);
    cy.get('[data-cy="codigoBarras"]').should('exist');
    cy.get('[data-cy="codigoBarras"]').focus();
    cy.get('[data-cy="codigoBarras"]').type('MNBDFH23');
    
    // cy.get('[data-cy="codigoManual"]').should('exist');
    // cy.get('[data-cy="codigoManual"]').focus();
    // cy.get('[data-cy="codigoManual"]').type('ERY455');
    
    cy.get('[data-cy="prodDescripcion"]').should('exist');
    cy.get('[data-cy="prodDescripcion"]').focus();
    // cy.get('[data-cy="prodDescripcion"]').type('MARCADOR PELIKAN NEGRO PUNTA GRUESA');
    cy.get('[data-cy="prodDescripcion"]').type('Lapiz Mirado Amarillo');
    
    cy.get('[data-cy="categoria"]').should('exist');
    cy.get('[data-cy="categoria"]').select('Lápices');
    
    cy.get('[data-cy="marca"]').should('exist');
    cy.get('[data-cy="marca"]').select('Pelikan');

    cy.get('[data-cy="cantStock"]').should('exist');
    cy.get('[data-cy="cantStock"]').focus();
    cy.get('[data-cy="cantStock"]').type('48');

    cy.get('[data-cy="valorVenta"]').should('exist');
    cy.get('[data-cy="valorVenta"]').focus();
    cy.get('[data-cy="valorVenta"]').type('1600');

    cy.get('[data-cy="valorDescuento"]').should('exist');
    cy.get('[data-cy="valorDescuento"]').focus();
    cy.get('[data-cy="valorDescuento"]').type('1400');

    cy.get('[data-cy="cantOferta"]').should('exist');
    cy.get('[data-cy="cantOferta"]').focus();
    cy.get('[data-cy="cantOferta"]').type('20');

    cy.get('[data-cy="valorOferta"]').should('exist');
    cy.get('[data-cy="valorOferta"]').focus();
    cy.get('[data-cy="valorOferta"]').type('1350');

    cy.get('[data-cy="prodObservaciones"]').should('exist');
    cy.get('[data-cy="prodObservaciones"]').focus();
    cy.get('[data-cy="prodObservaciones"]').type('Lápiz sencillo sin punta');
    
    // cy.get('[data-cy="botonAdjuntar"]').should('exist');
    // cy.get('[data-cy="botonAdjuntar"]').click();
    // cy.get('[data-cy="botonAdjuntar"]').click();
    // cy.get('[data-cy="botonAdjuntar"]').click();
    
    cy.get('[data-cy="form-producto"]').should('exist');
    cy.get('[data-cy="form-producto"]').submit();
  });

});
