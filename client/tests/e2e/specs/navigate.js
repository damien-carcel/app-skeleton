describe("Navigate on the home page", () => {
  it("Visits the app root url", () => {
    cy.visit("/");
    cy.contains("h1", "Welcome to Your Vue.js App");
  });

  it("Access the user list by following the navigation link", () => {
    cy.visit("/");
    cy.get('a[href="/users"]').click();
    cy.contains("h1", "Welcome to your future user list");
  });
});
