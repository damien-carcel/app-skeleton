describe('Navigate on the home page', () => {
  it('Visits the app root url', () => {
    cy.visit('/');
    cy.contains('p', 'Hello world!');
  });
});
