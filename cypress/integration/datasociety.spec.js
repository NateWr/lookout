describe('Data & Society', () => {
	it('Gets reports', () => {
		var articles = [];
		var save = ($el) => {
			var authors = [];
			$el.find('.library-people a')
				.each(function(i) {
					authors.push(Cypress.$(this).text().trim());
				});
			var date = $el.find('.library-column-date');
			articles.push({
				title: $el.find('.library-title a').html().trim(),
				url: $el.find('.library-title a').attr('href').trim(),
				date: date.text().trim(),
				dateText: date ? date.text().trim() : '',
				author: authors.join(', '),
				description: $el.find('.library-excerpt').html().trim(),
				publication: 'Data & Society',
				retrieved_at: Date.now()
			})
		}
		cy.visit('https://datasociety.net/library/?type=report');
		cy.get('.library-result')
			.each(save)
			.then(() => {
				cy.visit('https://datasociety.net/library/?type=blog-posts');
				cy.get('.library-result')
					.each(save)
					.then(() => {
						cy.save('datasociety', articles);
					})
			})
	})
})