
// Collect plugins to initialize them 
// in App Router

var SubblyPlugins = 
{
    list: []

  , register: function( plugin )
    {
      SubblyPlugins.list.push( plugin )
    }

  , getList: function()
    {
      return SubblyPlugins.list
    }
}
