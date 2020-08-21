describe('Jacobin', () => {
	it('Gets blog posts', () => {
		var articles = [];
		cy.visit('https://jacobinmag.com/blog');
		cy.get('.ar-mn__article')
			.each(($el, index, list) => {
				articles.push({
					title: $el.find('.ar-mn__title a').html().trim(),
					url: 'https://jacobinmag.com/' + $el.find('.ar-mn__title a').attr('href').trim(),
					date: $el.find('time').attr('datetime').trim(),
					dateText: $el.find('time').text().trim(),
					author: $el.find('.ar-mn__author a').text().trim(),
					description: $el.find('.ar-mn__summary').html().trim(),
					publication: 'Jacobin',
					retrieved_at: Date.now()
				})
			})
			.then(() => {
				cy.save('jacobinmag', articles);
			})
	})
})