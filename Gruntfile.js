module.exports = function ( grunt ) {
	let compress = {},
		clean = {},
		compressDirs = [
			'',
			'agency',
			'architecture',
			'automotive',
			'blogging',
			'bookstore',
			'construction',
			'education',
			'fashion',
			'fitness',
			'freelancer',
			'hotel',
			'landing',
			'law',
			'main',
			'medical',
			'photgraphy',
			'restaurant',
			'shop',
			'travel',
			'wedding',
		];

	for ( let name of compressDirs ) {
		let taskName = name ? name : 'child',
			dirName = `kalium-child-${name}`;

		if ( '' === name ) {
			dirName = 'kalium-child';
		}

		// Clean
		clean[ taskName ] = [`${dirName}.zip`];

		// Compress
		compress[ taskName ] = {
			options: {
				archive: `${dirName}.zip`,
			},
			files: [
				{
					src: [`${dirName}/**`],
				}
			],
		};
	}

	grunt.initConfig( {
		compress: compress,
		clean: clean,
	} );

	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-compress' );

	grunt.registerTask( 'default', [
		'clean',
		'compress',
	] );

	grunt.registerTask( 'zip', function ( childTheme ) {
		if ( compress.hasOwnProperty( childTheme ) ) {
			// console.log( `clean:${childTheme}`, `compress:${childTheme}` )
			grunt.task.run( [`clean:${childTheme}`, `compress:${childTheme}`] );
		}
	} );
}