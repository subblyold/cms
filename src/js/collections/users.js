
Components.Collection.Users = Components.Collection.List.extend(
{
    model:       Components.Model.User
  , serviceName: 'users'

  , comparator: function( model )
    {
        return model.displayName()
    }
})