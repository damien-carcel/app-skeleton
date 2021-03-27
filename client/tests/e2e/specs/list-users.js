describe('Navigate on the home page', () => {
  beforeEach(() => {
    cy.task('db:populate', {
      firstname: 'Damien',
      lastname: 'Carcel',
      email: 'damien.carcel@gmail.com',
      password: 'P@ssw0rd',
    });
  });

  it('Shows the list of users', () => {
    cy.visit('/');
    cy.contains('li', 'Damien Carcel');
  });
});
