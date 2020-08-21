Cypress.Commands.add('save', (path, data) => {
	cy.writeFile(`storage/app/scans/${path}/${Date.now()}.json`, JSON.stringify(data));
})