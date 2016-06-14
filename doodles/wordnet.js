var wordnet = require('wordnet');

wordnet.lookup('define', function(err, definitions) {

    definitions.forEach(function(definition) {
        console.log('  words: %s', words.trim());
        console.log('  %s', definition.glossary);
    });

});