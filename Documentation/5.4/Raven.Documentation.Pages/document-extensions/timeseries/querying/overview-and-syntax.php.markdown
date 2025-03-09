﻿# Querying Time Series: Overview & Syntax
---

{NOTE: }

* Querying time series entries enables comprehending how a process gradually populates a time series over time and locating documents related to chosen time series entries.

* Querying time series data is native to RavenDB's queries.  
  Clients can express time series queries in high-level LINQ expressions or directly in [RQL](../../../client-api/session/querying/what-is-rql).

* Queries can be executed as dynamic queries or over [time series indexes](../../../document-extensions/timeseries/indexing).  

* In this page:  
  * [Time series query capabilities](../../../document-extensions/timeseries/querying/overview-and-syntax#time-series-query-capabilities)
  * [Server and client queries](../../../document-extensions/timeseries/querying/overview-and-syntax#server-and-client-queries)  
  * [Dynamic and index queries](../../../document-extensions/timeseries/querying/overview-and-syntax#dynamic-and-index-queries)  
  * [Scaling query results](../../../document-extensions/timeseries/querying/overview-and-syntax#scaling-query-results)  
  * [RQL syntax](../../../document-extensions/timeseries/querying/overview-and-syntax#rql-syntax)  
     * [`select timeseries` syntax](../../../document-extensions/timeseries/querying/overview-and-syntax#section)  
     * [`declare timeseries` syntax](../../../document-extensions/timeseries/querying/overview-and-syntax#section-1)  
  * [Combine time series and custom functions](../../../document-extensions/timeseries/querying/overview-and-syntax#combine-time-series-and-custom-functions)  
  * [Use Studio To experiment](../../../document-extensions/timeseries/querying/overview-and-syntax#use-studio-to-experiment)  

{NOTE/}

---

{PANEL: Time series query capabilities}

Time series query can -  

* [Choose a range of time series entries](../../../document-extensions/timeseries/querying/choosing-query-range) to query from.  
* [Filter](../../../document-extensions/timeseries/querying/filtering) time series entries by their tags, values and timestamps. 
* [Aggregate](../../../document-extensions/timeseries/querying/aggregation-and-projections)  time series entries into groups by a chosen time resolution,  
  e.g. gather the prices of a stock that's been collected over the past two months to week-long groups.   
  Entries can also be aggregated by their tags.  
* Select entries by various criteria, e.g. by the min and max values of each aggregated group,  
  and [project](../../../document-extensions/timeseries/querying/aggregation-and-projections) them to the client.  
* Calculate [statistical measures](../../../document-extensions/timeseries/querying/statistics): the percentile, slope, or standard deviation of a time series.  

{PANEL/}

{PANEL: Server and client queries}

Time series queries are executed by the server and their results are projected to the client,  
so they require very little client computation resources.  

* The server runs time series queries using RQL.  
* Clients can phrase time series queries in **raw RQL** or using high level **LINQ expressions**.  
  High level queries are translated to RQL by the client before sending them to the server for execution.  

{PANEL/}

{PANEL: Dynamic and index queries}

* **Dynamic queries**:  
  * Time series indexes are Not created automatically by the server when making a dynamic query.  
  * Use dynamic queries when time series you query are not indexed,  
    or when you prefer that RavenDB would choose an index automatically. See [queries always use an index](../../../client-api/session/querying/how-to-query#queries-always-provide-results-using-an-index).  
    E.g. -

    {CODE-BLOCK: javascript}
// Query for time series named "HeartRates" in employees hired after 1994
from Employees as e
where HiredAt > "1994-01-01"
select timeseries(
    from HeartRates
)
    {CODE-BLOCK/}

* **Index queries**:
  * Static time series indexes can be created by clients (or using Studio).  
  * Examples of querying a static time series index can be found in [querying time series indexes](../../../document-extensions/timeseries/querying/using-indexes).

{PANEL/}

{PANEL: Scaling query results}

* Time series query results can be **scaled**, multiplied by some number. 
  This doesn't change the values themselves, only the output of the query. 
  Scaling can serve as a stage in a data processing pipeline, or just for the purposes of displaying the data in a more understandable format. 

* There are several use cases for scaling. 
  For example, suppose your time series records the changing speeds of different vehicles as they travel through a city,
  with some data measured in miles per hour and others in kilometers per hour. Here, scaling can facilitate unit conversion.

* Another use case involves the compression of time series data. 
  Numbers with very high precision (i.e., many digits after the decimal point) are less compressible than numbers with low precision.
  Therefore, for efficient storage, you might want to change a value like `0.000018` to `18` when storing the data.  
  Then, when querying the data, you can scale by `0.000001` to restore the original value. 

* Scaling is a part of both RQL and LINQ syntax:  

  * In **LINQ**, use `.Scale(<double>)`.
  * In **RQL**, use `scale <double>` in a time series query, and input your scaling factor as a double.   

---

#### Example:  

{CODE-TABS}
{CODE-TAB-BLOCK:php:LINQ}
var query = session.Query<User>()
    .Select(p => RavenQuery.TimeSeries(p, "HeartRates")
        .Scale(10)
        .ToList())
    .ToList();

// The value in the query results is 10 times the value stored on the server
var scaledValue = query[0].Results[0].Values[0];
{CODE-TAB-BLOCK/}
{CODE-TAB-BLOCK:sql:RQL}
from Users
select timeseries(
    from HeartRates
    scale 10
)
{CODE-TAB-BLOCK/}
{CODE-TABS/}

{PANEL/}

{PANEL: RQL syntax}

A typical time series query can start by locating the documents whose time series we want to query.  
For example, we can query for employees above 30:

{CODE-BLOCK: javascript}
from Employees as e
where Birthday < '1994-01-01'
{CODE-BLOCK/}

Then, you can query their time series entries using either of the following two equivalent syntaxes:

* [`select timeseries` syntax](../../../document-extensions/timeseries/querying/overview-and-syntax#section)  
* [`declare timeseries` syntax](../../../document-extensions/timeseries/querying/overview-and-syntax#section-1)  

---

### `select timeseries`

This syntax allows you to encapsulate your query's time series functionality in a `select timeseries` section.  

{CODE-BLOCK: javascript}
// Query for entries from time series "HeartRates" for employees above 30
// ======================================================================

// This clause locates the documents whose time series we want to query:
from Employees as e 
where Birthday < '1994-01-01'
 
// Query the time series that belong to the matching documents:
select timeseries (   // The `select` clause defines the time series query.  
    from HeartRates   // The `from` keyword is used to specify the time series name to query.  
)
{CODE-BLOCK/}

---

### `declare timeseries`

This syntax allows you to declare a time series function (using `declare timeseries`) and call it from your query.  
It introduces greater flexibility to your queries as you can, for example, pass arguments to the time series function.  

Here is a query written in both syntaxes.  
It first queries for users above 30. If they possess a time series named "HeartRates", it retrieves a range of its entries.

<br>

| With Time Series Function  | Without Time Series Function  |
|----------------------------|-------------------------------|
| {CODE-BLOCK: javascript}
// declare the time series function:
declare timeseries ts(jogger) {
    from jogger.HeartRates 
    between 
       "2020-05-27T00:00:00.0000000Z"
      and 
       "2020-06-23T00:00:00.0000000Z"
}

from Users as jogger
where Age > 30
// call the time series function
select ts(jogger)
{CODE-BLOCK/} | {CODE-BLOCK: javascript} 
from Users as jogger
where Age > 30
select timeseries(
    from HeartRates 
    between 
       "2020-05-27T00:00:00.0000000Z"
      and 
       "2020-06-23T00:00:00.0000000Z")
    {CODE-BLOCK/} |

{PANEL/}

{PANEL: Combine time series and custom functions}

* You can declare and use both time series functions and custom functions in a query.  
  The custom functions can call the time series functions, pass them arguments, and use their results.

* In the example below, a custom function (`customFunc`) is called by the query `select` clause 
  to fetch and format a set of time series entries, which are then projected by the query.  
  The time series function (`tsQuery`) is called to retrieve the matching time series entries.

* The custom function returns a flat set of values rather than a nested array, to ease the projection of retrieved values.

* Note the generated RQL, where the custom function is translated to a [custom JavaScript function](../../../client-api/session/querying/what-is-rql#declare).
 
{CODE-BLOCK:javascript}
// The time series function:
// =========================
declare timeseries tsQuery(user) {
    from user.HeartRates
    where (Values[0] > 100)
}

// The custom JavaScript function:
// ===============================
declare function customFunc(user) {
    var results = [];

    // Call the time series function to retrieve heart rate values for the user
    var r = tsQuery(user);

    // Prepare the results
    for(var i = 0 ; i < r.Results.length; i ++) {
        results.push({
            Timestamp: r.Results[i].Timestamp, 
            Value: r.Results[i].Values.reduce((a, b) => Raven_Max(a, b)),
            Tag: r.Results[i].Tag  ?? "none"})
    }
    return results;
}

// Query & project results:
// ========================
from "Users" as user
select
    user.Name,
    customFunc(user) as timeSeriesEntries // Call the custom JavaScript function
{CODE-BLOCK/}

This is the custom `ModifiedTimeSeriesEntry` class that is used in the above LINQ sample:  

{CODE:php DefineCustomFunctions_ModifiedTimeSeriesEntry@DocumentExtensions\TimeSeries\TimeSeriesTests.php /}

{PANEL/}

{PANEL: Use Studio to experiment}

You can use [Studio](../../../studio/database/document-extensions/time-series) to try the RQL samples provided in this article and test your own queries.  

!["Time Series Query in Studio"](images/time-series-query.png "Time Series Query in Studio")

{PANEL/}


## Related articles

**Time Series Overview**  
[Time Series Overview](../../../document-extensions/timeseries/overview)  

**Studio Articles**  
[Studio Time Series Management](../../../studio/database/document-extensions/time-series)  

**Time Series Indexing**  
[Time Series Indexing](../../../document-extensions/timeseries/indexing)  

**Time Series Queries**  
[Range Selection](../../../document-extensions/timeseries/querying/choosing-query-range)  
[Filtering](../../../document-extensions/timeseries/querying/filtering)  
[Aggregation and Projection](../../../document-extensions/timeseries/querying/aggregation-and-projections)  
[Indexed Time Series Queries](../../../document-extensions/timeseries/querying/using-indexes)  
[Statistical Measures](../../../document-extensions/timeseries/querying/statistics)  

**Policies**  
[Time Series Rollup and Retention](../../../document-extensions/timeseries/rollup-and-retention)  
