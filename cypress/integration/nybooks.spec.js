describe('The New York Review of Books', () => {
	it('Gets current issue', () => {
		var articles = [];
		var date = '';
		var dateText = '';
		cy.visit('https://www.nybooks.com');
		cy.get('header a:contains("Current Issue")').click();
		cy.get('.issue_header time')
			.then(($el) => {
				dateText = $el.text().split('•')[0].trim();
				date = new Date(
						Date.parse(dateText)
					).toISOString().split('T')[0];
			})
		cy.get('.current_issue > .articles_list article')
			.each(($el, index, list) => {
				var reviews = [];
				$el.find('p').each(function() {
					reviews.push(Cypress.$(this).html().trim());
				});
				articles.push({
					title: $el.find('h2 a').html().trim(),
					url: $el.find('h2 a').attr('href').trim(),
					date: date,
					dateText: dateText,
					author: $el.find('.author').text().trim(),
					description: reviews.join(', '),
					available: !!$el.find('.icon-lock').length,
					publication: 'The New York Review of Books',
					retrieved_at: Date.now()
				})
			})
			.then(() => {
				cy.save('nybooks', articles);
			})
	})
})