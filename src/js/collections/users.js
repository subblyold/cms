
Components.Subbly.Collection.Users = Components.Subbly.Collection.List.extend(
{
    model:       Components.Subbly.Model.User
  , serviceName: 'users'

  , comparator: function( model )
    {
        return model.displayName()
    }
})