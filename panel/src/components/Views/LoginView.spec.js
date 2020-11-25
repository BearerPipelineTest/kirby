describe('LoginView', () => {

  before(() => {
    cy.visit('/env/install/minimal');
    cy.visit('/env/user/test');
  });

  beforeEach(() => {
    cy.visit('/');
  });

  it('should fail', () => {
    cy.get('input[type="email"]').type("test@getkirby.com");
    cy.get('input[type="password"]').type("abcdefgh");
    cy.get('form').submit();
    cy.get('.k-login-alert').should('contain', 'The passwords do not match');
  });

  it('should login and redirect to SiteView', () => {
    cy.get('input[type="email"]').type("test@getkirby.com");
    cy.get('input[type="password"]').type("12345678");
    cy.get('form').submit();

    cy.url().should('include', '/site');
  });

});
