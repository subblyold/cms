cms
===

[![Build Status](https://travis-ci.org/subbly/cms.svg)](https://travis-ci.org/subbly/cms)
[![Total Downloads](https://poser.pugx.org/subbly/cms/downloads.svg)](https://packagist.org/packages/subbly/cms)
[![Latest Stable Version](https://poser.pugx.org/subbly/cms/v/stable.svg)](https://packagist.org/packages/subbly/cms)
[![Latest Unstable Version](https://poser.pugx.org/subbly/cms/v/unstable.svg)](https://packagist.org/packages/subbly/cms)
[![License](https://poser.pugx.org/subbly/cms/license.svg)](https://packagist.org/packages/subbly/cms)

## base models
Shop
Products  
Addresses  
Inventory  
Orders  
Shipments  
Payments  
Payment State and Workflow  
Taxation  
Pricing  
Promotions  
Users and Groups  
Customer
Currencies  
Locales  
Content  
Settings  


### shops table

les settings suivants sont sauvegardés au format JSON car il ne nécéssite pas d'être requêtés et cela facilite l'ajout d'entrés pour les plugin sans alterer la table :
- Raison social
- SIRET
- Numéro TVA
