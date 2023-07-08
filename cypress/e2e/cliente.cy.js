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
      
      cy.get('[data-cy="cliente"]').should('exist');
      cy.get('[data-cy="cliente"]').click();
      
      // Llenando el formulario de producto
      cy.wait(500);
      
      cy.get('[data-cy="Cli_Ced_Nit"]').should('exist');
      cy.get('[data-cy="Cli_Ced_Nit"]').focus();
      cy.get('[data-cy="Cli_Ced_Nit"]').type('1049794511');
      
      cy.get('[data-cy="Cli_RazonSocial"]').should('exist');
      cy.get('[data-cy="Cli_RazonSocial"]').focus();
      cy.get('[data-cy="Cli_RazonSocial"]').type('Laura Andrea Fandiño Morales');

      cy.get('[data-cy="Cli_Tel"]').should('exist');
      cy.get('[data-cy="Cli_Tel"]').focus();
      cy.get('[data-cy="Cli_Tel"]').type('3212851593');

      cy.get('[data-cy="Cli_Email"]').should('exist');
      cy.get('[data-cy="Cli_Email"]').focus();
      cy.get('[data-cy="Cli_Email"]').type('laura.fandino.morales@hotmail.com');

      cy.get('[data-cy="Cli_Direccion"]').should('exist');
      cy.get('[data-cy="Cli_Direccion"]').focus();
      cy.get('[data-cy="Cli_Direccion"]').type('Carrera 6 # 12 - 05');

      cy.get('[data-cy="Cli_Departamento"]').should('exist');
      cy.get('[data-cy="Cli_Departamento"]').select('BOYACA');
        
      cy.get('[data-cy="Cli_Ciudad"]').should('exist');
      cy.get('[data-cy="Cli_Ciudad"]').select('SUTATENZA');
      
      cy.get('[data-cy="form-cliente"]').should('exist');
      cy.get('[data-cy="form-cliente"]').submit();
    });
  
  });
  