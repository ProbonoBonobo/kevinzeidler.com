/**
 * Created by kevinzeidler on 12/20/16.
 */

var configMap = JSON.parse( window.localStorage.getItem("config")) || {};
var tempStorage = window.tempStorage || {};
var db = window.localStorage || {};


    
    var Utils = (function(config) {
        var verbose     = true;
        var initialized = false;
        var pointers = false;
        var env = false;
        var state = {};
        
        function acquired() {
            return (config && typeof(config) === "object" && Object.keys( config ).length > 0);
        }
    
        function randRange(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }
        
        function init(state) {
            var deserialized = JSON.parse(config);
            if ( Object.keys( deserialized ).length ) {
         
                state.bootImage          = deserialized;
                state.initialized        = true;
                state.env                = "local";
                state.pointers           = {};
                state.prettyBackgrounds  = {
                    currentIndex        : randRange(0, 10),
                    lastUpdate          : 0,
                    updateInterval    : 60000,
                    source :       deserialized.profiles.settings.local.namedSrcs.prettyBackgrounds.source,
                    destination :  tempStorage.prettyBackgrounds
                }
            }
            return state;
        }
        
        function malloc(len) {
            // return a buffer preinitialized to the given length
            var ker    = [];
            ker.length = len;
            return ker;
        }
        
        function makePaddedString(len) {
            // return a string of whitespace satisfying the provided length parameter
            var container = malloc( len );
            return container.fill( " " ).join( "" );
        }
        
        function peek(arr) {
            console.assert( arr instanceof Array, { "message" : "not an array", "arr" : arr } );
            var lastItem;
            if ( arr.length > 0 )
                lastItem = arr[ arr.length - 1 ];
            return lastItem;
        }
        
        function dfs(obj, root, paddingFactor, objCtr, itemCtr) {
            
            console.assert( typeof(obj) == "object",
                            { "message" : "dfs called on non-object", "obj" : obj, "root" : root }
            );
            console.assert( root instanceof Array,
                            { "message" : "invalid dfs root (expected Array)", "obj" : obj, "root" : root }
            );
            
            var rootArr             = root,
                localRoot           = root.join( "." ),
                localDepth          = root.length,
                localKeys           = Object.keys( obj ),
                myType              = "Object",
                spacesPerDepthLevel = paddingFactor || 3,
                parentPad           = makePaddedString( (localDepth - 1) * spacesPerDepthLevel ),
                childPad            = makePaddedString( localDepth * spacesPerDepthLevel ),
                childValue          = "Unknown",
                childKey            = "",
                childPath           = [];
            
            if ( Array.isArray( obj ) || obj instanceof Array )
                myType = "Array";
            
            var myParent;
            (root.length === 1) ? myParent = window || "" : myParent = root[ root.length - 2 ];
            
            // console.log(parentPad + "Created.");
            console.log( "\n" + parentPad + "======== " + "NAMESPACE : " + root.join( "\." )
                                                                               .toUpperCase() + " =======\n"
            );
            console.log( obj );
            console.log( "\n" + parentPad + "CURRENT MODULE: " + peek( root ) + " (DEPTH: " + root.length + ")" );
            console.log( parentPad + "PARENT MODULE : " + myParent );
            
            console.log( parentPad + "An " + myType + " of " + localKeys.length + " keys: " );
            for ( var index = 0; index < localKeys.length; index++ ) {
                var propName = localKeys[ index ];
                console.log( parentPad + " " + index + ". \'" + propName.toUpperCase() + "\' a(n) " + typeof(obj[ propName ]) );
            }
            // console.log( parentPad + "root [localRoot] : " + root + "[" + localRoot + "]" );
            // console.log( parentPad + "localDepth       : " + localDepth );
            // console.log( parentPad + "parentName       : " + myParent );
            // console.log( parentPad + "Key              : " + peek( root ) );
            // console.log( parentPad + "Root             : " + localRoot );
            // console.log( parentPad + "Depth            : " + localDepth );
            //
            console.log( "" );
            console.log( parentPad + localRoot + " => " + " {" );
            
            for ( i in obj ) {
                childPath = [ root, i ];
                childKey  = childPath.join( "." );
                if ( obj.hasOwnProperty( i ) ) {
                    childValue = obj[ i ];
                    if ( typeof(childValue) === "object" ) {
                        console.log( parentPad + "Starting crawl of " + childKey );
                        dfs( obj[ i ], childPath, spacesPerDepthLevel );
                        
                    } else {
                        console.log( childPad + childKey + " => " + childValue + " [" + typeof(childValue) + "]" );
                    }
                } else {
                    console.log( childPad + "// skipping " + i + " because it's not a property of " + childPath );
                }
                
            }
            console.log( parentPad + "==================================" );
            console.log( "" );
            console.log( parentPad + "Finished crawling " + localRoot );
        }
        
        function loadImagesFromDirectory(dir, ext, db) {
            var path       = dir || '/kevinzeidler.com/histones/img/backgrounds/',
                filetype   = ext || 'jpg',
                xpathQuery = 'a[href$=' + filetype + ']';
            
            var response = {
                'directory' : "",
                'files'     : []
            };
            
            // get auto-generated page
            $.ajax( { url : path } ).then( function(html) {
                                               // create temporary DOM element
                                               var document = $( html ),
                                                   filename,
                                                   fqn;
                
                                               // log the directory name
                                               response.directory = path;
                
                                               // find all links ending with .pdf
                                               document.find( xpathQuery ).each( function() {
                    
                                                                                     var id  = $( this ).attr( 'href' );
                                                                                     var fqn = location.host + response.directory + id;
                                                                                     console.log( id );
                                                                                     console.log( fqn );
                                                                                     response.files.push( {
                                                                                                              'filename'     : id,
                                                                                                              'absolutePath' : fqn
                                                                                                          }
                                                                                     );
                                                                                     // response.fileURLs.push( $( this ).attr( 'href' ) );
                                                                                 }
                                               );
                                               console.log( "Found " + response.files.length + " files of type " + filetype + "." );
                                               console.log( response );
                                           }
            );
            tempStorage.prettyBackgrounds.sourceMappings = response;
            console.log("response cached.");
            console.log(tempStorage);
            return response;
        }
        
        function tryToFetch(maybeUrl) {
            if ( verbose )
                console.log( "[NOTICE] : Utils.tryToFetch => Fetching " + maybeUrl + "... " );
            $.ajax( {
                        type    : 'HEAD',
                        url     : maybeUrl,
                        success : function() {
                            console.log( "[NOTICE] : Utils.tryToFetch => It's valid! Returning true." );
                            return true;
                        },
                        error   : function() {
                            console.log( "[NOTICE] : Utils.tryToFetch => gurr ya shit is BROKE! Returning false." );
                            return false;
                        }
                    }
            );
        }
        
        function randomChoice(choices) {
            var index = Math.floor( Math.random() * choices.length );
            return choices[ index ];
        }
        
        function callerID(s) {
            var frags     = s.split( "(" );
            var extracted = frags[ 0 ].trim();
            
            if ( verbose ) {
                console.log( "CallerID received " + s );
                console.log( "CallerID returning " + extracted );
            }
            
            return extracted;
        }
        state = init({});
        
        return {
            randRange        : randRange,
            mountImages      : loadImagesFromDirectory,
            isValidURL       : tryToFetch,
            randomChoice     : randomChoice,
            callerID         : callerID,
            dfs              : dfs,
            malloc           : malloc,
            makePaddedString : makePaddedString,
            config           : config,
            initialized      : acquired( config ),
            init : init,
            state : function() {
                return JSON.parse(state);
            }
        }
    })( JSON.stringify( configMap ));
    
