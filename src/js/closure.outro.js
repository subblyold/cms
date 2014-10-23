
  // On DOMready
  // $(function()
  // {
    // console.info('dom ready')
    
    // App = new Tapioca( tapiocaConfig )

    // window.Tapioca = App
  // })

})(window); // end closure


// Plugins holder
var subblyPlugins = {}


// In case we forget to take out console statements. 
// IE becomes very unhappy when we forget. Let's not make IE unhappy
if(typeof(console) === 'undefined')
{
  var console = {}
  console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function () {};
}
