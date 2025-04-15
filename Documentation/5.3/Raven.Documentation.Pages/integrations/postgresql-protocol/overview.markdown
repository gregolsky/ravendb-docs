﻿# PostgreSQL Protocol: Overview

* RavenDB implements the PostgreSQL protocol, allowing applications and libraries that 
  use PostgreSQL, e.g. [Power BI](../../integrations/postgresql-protocol/power-bi), to 
  retrieve data from a RavenDB database.  

* To use RavenDB as a PostgreSQL server you need -  
   * a [license](../../start/licensing/licensing-overview) that enables the PostgreSQL Protocol.  
   * To explicitly enable PostgreSQL in your [settings](../../server/configuration/configuration-options).  

* [Installing](../../start/installation/setup-wizard) RavenDB as 
  a [secure](../../server/security/overview) server allows you to authenticate 
  PostgreSQL clients, granting access only to clients that provide the proper credentials.  

---

{NOTE: }

* In this page:  
  * [Enabling PostgreSQL support](../../integrations/postgresql-protocol/overview#enabling-postgresql-support)  
     * [License](../../integrations/postgresql-protocol/overview#license)  
     * [Settings](../../integrations/postgresql-protocol/overview#settings)  
     * [PostgreSQL Port](../../integrations/postgresql-protocol/overview#postgresql-port)  
  * [Security](../../integrations/postgresql-protocol/overview#security)  
{NOTE/}

---

{PANEL: Enabling PostgreSQL support}

### License

* Your RavenDB license determines which features are available for your server.  
* Visit Studio's [About](../../start/licensing/licensing-overview#manage-license-view) 
  page to find which features are included in your license.  
* PostgreSQL is enabled for all licenses.  
* To [use Power BI with RavenDB as its PostgreSQL server](../../integrations/postgresql-protocol/power-bi), 
  your license must explicitly enable Power BI.  
  If your current license doesn't include Power BI Support, you can acquire one that does [here](https://ravendb.net/buy).  

---

### Settings

* PostgreSQL protocol support must be explicitly enabled in your [settings](../../server/configuration/configuration-options#settings.json).  
  Add this line to your server's `settings.json` file to enable the PostgreSQL protocol:  
  {CODE-BLOCK:json}
"Integrations.PostgreSQL.Enabled": true
  {CODE-BLOCK/}
* PostgreSQL is an experimental feature. To enable it, enable RavenDB's 
  [Experimental Features](../../server/configuration/core-configuration#features.availability) 
  by adding this line to your server's `settings.json` file:  
  {CODE-BLOCK:json}
"Features.Availability": "Experimental"
  {CODE-BLOCK/}  

---

### PostgreSQL Port

* To access RavenDB, your clients need not only its **URL** but also its 
  PostgreSQL **Port** number.  
  By default, the port number is *5433*.  
* To use a different port, add the following line to your settings.json file, with a port number 
  of your choice:  
  {CODE-BLOCK:json}
"Integrations.PostgreSQL.Port": 5433
     {CODE-BLOCK/}

{PANEL/}

{PANEL: Security}

Allowing just any client to connect to your database (via PostgreSQL or otherwise) 
without authentication is risky, and should in general be avoided.  
{WARNING: }
If RavenDB is not set as a secure server, it will require no authentication over the PostgreSQL protocol.  
{WARNING/}

To allow access only for authorized clients -  

* Set RavenDB as a [Secure Server](../../server/security/overview).  
  This will allow RavenDB to authenticate PostgreSQL clients, in addition 
  to many other security measures this setup provides.  
* Create [PostgreSQL Credentials](../../studio/database/settings/integrations) using RavenDB Studio.  
  PostgreSQL credentials are a **user name** and a **password**, that a client 
  would have to provide in order to access the database.  

{PANEL/}

## Related articles

**Integrations**  
[Integrations: Power BI](../../integrations/postgresql-protocol/power-bi)  

**Studio**  
[Studio: Integrations and Credentials](../../studio/database/settings/integrations)  

**Security**  
[Setup Wizard](../../start/installation/setup-wizard)  
[Security Overview](../../server/security/overview)  

**Settings**  
[settings.json](../../server/configuration/configuration-options#settings.json)  

**Additional Links**  
[Microsoft Power BI Download Page](https://powerbi.microsoft.com/en-us/downloads)  



