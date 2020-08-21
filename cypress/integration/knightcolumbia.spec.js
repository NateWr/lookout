describe('Knight 1st Amendment Institute', () => {
	it('Gets series on Tech Giants, Monopoly Power, and Public Discouse', () => {
		var articles = [];
		cy.visit('https://knightcolumbia.org/research/the-tech-giants-monopoly-power-and-public-discourse');
		cy.get('.landing-stack-article')
			.each(($el, index, list) => {
				var dateText = $el.find('.landing-stack-date').text().trim();
				var date = new Date(
						Date.parse(dateText)
					).toISOString().split('T')[0];
				articles.push({
					title: $el.find('.landing-stack-hed a').html().trim(),
					url: 'https://knightcolumbia.org' + $el.find('.landing-stack-hed a').attr('href').trim(),
					date: date,
					dateText: dateText,
					author: $el.find('.landing-stack-author a').text().trim(),
					description: $el.find('.landing-stack-subhed').html().trim(),
					publication: 'Tech Giants, Monopoly Power, and Public Discouse - Knight 1st Amendment Institute',
					retrieved_at: Date.now()
				})
			})
			.then(() => {
				cy.save('knightcolumbia', articles);
			})
	})
})