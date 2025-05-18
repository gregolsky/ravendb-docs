# What Affects Vector Search Results
---

{NOTE: }

* This article explains why vector search results might not always return what you expect, even when relevant documents exist.
  It applies to both [dynamic vector search queries](../../ai-integration/vector-search/vector-search-using-dynamic-query) and
  [static-index vector search queries](../../ai-integration/vector-search/vector-search-using-static-index).

* Vector search in RavenDB uses the [HNSW](https://en.wikipedia.org/wiki/Hierarchical_navigable_small_world) algorithm (Hierarchical Navigable Small World)
  to index and search high-dimensional vector embeddings efficiently.
  This algorithm prioritizes performance, speed, and scalability over exact precision.
  Due to its approximate nature, results may occasionally exclude some relevant documents.

* Several **indexing-time parameters** affect how the vector graph is built, and **query-time parameters** affect how the graph is searched.
  These settings influence the trade-off between speed and accuracy.

* In this article:
  * [The approximate nature of HNSW](../../ai-integration/vector-search/what-affects-vector-search-results#the-approximate-nature-of-hnsw)
  * [Indexing-time parameters](../../ai-integration/vector-search/what-affects-vector-search-results#indexing-time-parameters)
  * [Query-time parameters](../../ai-integration/vector-search/what-affects-vector-search-results#query-time-parameters)
  * [Using exact search](../../ai-integration/vector-search/what-affects-vector-search-results#using-exact-search)
    
{NOTE/}

---

{PANEL: The approximate nature of HNSW}

* **Graph structure**:  

  * HNSW builds a multi-layer graph, organizing vectors into a series of layers:  
    Top layers are sparse and support fast, broad navigation.  
    The bottom layer is dense and includes all indexed vectors for fine-grained matching.  
  * Each node (vector) is connected only to a limited number of neighbors, selected as the most relevant during indexing (graph build time).
    This limitation is controlled by the [Indexing-time parameters](../../ai-integration/vector-search/what-affects-vector-search-results#indexing-time-parameters) described below.
  * This structure speeds up search but increases the chance that a relevant document is not reachable -  
    especially if it's poorly connected.

* **Insertion order effects**:  

  * Because the HNSW graph is append-only and built incrementally,  
    the order in which documents are added, updated, or deleted can affect the final graph structure.  
    Deleted vectors are not physically removed, but marked as deleted (soft-deleted).
  * This means that two databases containing the same documents may return different vector search results
    if the documents were indexed in a different order.

* **Greedy search**:  

  * HNSW uses a greedy search strategy to perform approximate nearest-neighbor (ANN) searches:  
    The search starts at the top layer from an entry point.  
    The algorithm then descends through the layers, always choosing the neighbor closest to the query vector.  
  * The algorithm doesn't exhaustively explore all possible paths, so it can miss the true global nearest neighbors -  
    especially if they are not well-connected in the graph.
  * The search is influenced by the [Query-time params](../../ai-integration/vector-search/what-affects-vector-search-results#query-time-parameters) described below.  
    Slight variations in graph structure or search parameters can lead to different results.
  * While HNSW offers fast search performance at scale and quickly finds points that are likely to be among the nearest neighbors,
    it does not guarantee exact results — only approximate matches are returned.  
    This behavior is expected in all ANN algorithms, not just HNSW or RavenDB.  
    If full accuracy is critical, consider using [Exact search](../../ai-integration/vector-search/what-affects-vector-search-results#using-exact-search) instead.

{PANEL/}

{PANEL: Indexing-time parameters}

The structure of the HNSW graph is determined at indexing time. 
RavenDB provides the following configuration parameters that control how the graph is built.
These parameters influence how vectors are connected and how effective the search will be.
They help keep memory usage and indexing time under control, but may also limit the graph’s ability to precisely represent all possible proximity relationships.

* **Number of edges**:  
   
  * This parameter controls how many connections (edges) each vector maintains in the HNSW graph.  
    Each node (vector) is connected to a limited number of neighbors in each layer — up to the value specified by this param.
    These edges define the structure of the graph and affect how vectors are reached during search.
  * A **larger** number of edges increases the graph’s density, improving connectivity and typically resulting in more accurate search results, 
    but it may also increase memory usage and slow down index construction.  
    A **smaller** value reduces memory usage and speeds up indexing, but can result in a sparser graph with weaker connectivity and reduced search accuracy.
  * With **static-indexes** -  
    This param can be set directly in the index definition. For example, see this [index definition](../../ai-integration/vector-search/vector-search-using-static-index#indexing-raw-text).  
    If not explicitly set, or when using **dynamic queries** -  
    the value is taken from the [Indexing.Corax.VectorSearch.DefaultNumberOfEdges](../../server/configuration/indexing-configuration#indexing.corax.vectorsearch.defaultnumberofedges) configuration key.  

* **Number of candidates at indexing time**:
   
  * During index construction, HNSW searches for potential neighbors when inserting each new vector into the graph.  
    This parameter (commonly referred to as _efConstruction_) controls how many neighboring vectors are considered during this process.
    It defines the size of the candidate pool - the number of potential links evaluated for each insertion.  
    From the candidate pool, HNSW selects up to the configured _number of edges_ for each node.
  * A **larger** candidate pool increases the chance of finding better-connected neighbors,  
    improving the overall accuracy of the graph.  
    A **smaller** value speeds up indexing and reduces resource usage,  
    but can result in a sparser and less accurate graph structure.   
  * With **static-indexes** -  
    This param can be set directly in the index definition. For example, see this [index definition](../../ai-integration/vector-search/vector-search-using-static-index#indexing-raw-text).  
    If not explicitly set, or when using **dynamic queries** -  
    the value is taken from the [Indexing.Corax.VectorSearch.DefaultNumberOfCandidatesForIndexing](../../server/configuration/indexing-configuration#indexing.corax.vectorsearch.defaultnumberofcandidatesforindexing) configuration key.

---

For all parameters that can be defined at indexing time (including the ones above),  
see [Parameters defined at index definition](../../ai-integration/vector-search/vector-search-using-static-index#parameters-defined-at-index-definition).

{PANEL/}

{PANEL: Query-time parameters}

Once the index is built, the following query-time parameters influence the vector search - controlling how the HNSW graph is traversed and how results are selected.
These parameters directly affect how many results are found, how similar they are to the input vector, and how they are ranked.

* **Number of Candidates at query time**:  
 
  * This parameter (commonly referred to as _efSearch_) controls how many nodes in the HNSW graph are evaluated during a vector search - 
    that is, how many candidates are considered before the search stops.  
    It defines the size of the priority queue used during the search: the number of best-so-far candidates that RavenDB will track and expand as it descends through the graph.
  * A **larger** value increases the breadth of the search, allowing the algorithm to explore a wider set of possible neighbors 
    and typically improving accuracy and the chances of retrieving all relevant results - but this comes at the cost of slower query performance.  
    A **smaller** value speeds up queries and reduces resource usage, but increases the chance of missing relevant results due to the more limited exploration.
  * This param can be set directly in the query. For example, see this [Query example](../../ai-integration/vector-search/vector-search-using-dynamic-query#querying-raw-text).  
    If not explicitly set, the value is taken from the [Indexing.Corax.VectorSearch.DefaultNumberOfCandidatesForQuerying](../../server/configuration/indexing-configuration#indexing.corax.vectorsearch.defaultnumberofcandidatesforquerying) configuration key.  

* **Minimum Similarity**:  
  
  * This parameter defines a threshold between `0.0` and `1.0` that determines how similar a vector must be to the query in order to be included in the results.
  * Vectors with a similarity score below this threshold are excluded from the results -  
    even if they would otherwise be among the top candidates.  
    Use this to filter out marginal matches, especially when minimum semantic relevance is important.
  * This param can be set directly in the query. For example, see this [Query example](../../ai-integration/vector-search/vector-search-using-dynamic-query#querying-raw-text).  
    If not explicitly set, the value is taken from the [Indexing.Corax.VectorSearch.DefaultMinimumSimilarity](../../server/configuration/indexing-configuration#indexing.corax.vectorsearch.defaultminimumsimilarity) configuration key.

* **Search Method**:  

  * You can choose between two vector search modes:  
    * **Approximate search** (default):  
      Uses the HNSW algorithm for fast, scalable search. While it doesn’t guarantee the absolute nearest vectors,  
      it is typically accurate and strongly recommended in most scenarios due to its performance.
    * **Exact search**:  
      Performs a full comparison against all indexed vectors to guarantee the closest matches.  
      This method is more accurate but much slower - learn more in [Using exact search](../../ai-integration/vector-search/what-affects-vector-search-results#using-exact-search) below.

---

For all parameters that can be defined at query time, see:  
Dynamic queries - [The dynamic query parameters](../../ai-integration/vector-search/vector-search-using-dynamic-query#the-dynamic-query-parameters).  
Static index queries - [Parameters used at query time](../../ai-integration/vector-search/vector-search-using-static-index#parameters-used-at-query-time).   

{PANEL/}

{PANEL: Using exact search}

* If you need precise control over results and want to avoid the approximations of HNSW,  
  you can perform an exact search instead.
  
* Exact search performs a full scan of the vector space, comparing the query vector to every indexed vector.  
  This guarantees that the true closest matches are returned.

* While exact search provides guaranteed accuracy, it is slower and more resource-intensive.  
  The approximate search is strongly recommended in most scenarios due to its performance.  
  Use exact search only when maximum precision is critical and you can tolerate the cost of a full scan.

* Exact search can be used with both static index queries and dynamic queries.  
  For example, see [Dynamic vector search - exact search](../../ai-integration/vector-search/vector-search-using-dynamic-query#dynamic-vector-search---exact-search).

{PANEL/}

## Related Articles

### Client API

- [RQL](../../client-api/session/querying/what-is-rql) 
- [Query overview](../../client-api/session/querying/how-to-query)

### Vector Search

- [Vector search using a dynamic query](../../ai-integration/vector-search/vector-search-using-dynamic-query.markdown)
- [Vector search using a static index](../../ai-integration/vector-search/vector-search-using-static-index.markdown)
- [Data types for vector search](../../ai-integration/vector-search/data-types-for-vector-search)

### Server

- [indexing configuration](../../server/configuration/indexing-configuration)
