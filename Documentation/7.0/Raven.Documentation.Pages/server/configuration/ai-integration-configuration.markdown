# Configuration: AI Integration
---

{NOTE: }

* The following configuration keys apply to integrating **AI-powered embeddings generation**:
 
  * Embeddings can be generated from your document content via [AI-powered tasks](../../ai-integration/generating-embeddings/overview) and stored in a dedicated collection in the database.  
  * When performing vector search queries, embeddings are also generated from the search term to compare against the stored vectors.

* In this article:
   * [Ai.Embeddings.MaxBatchSize](../../server/configuration/ai-integration-configuration#ai.embeddings.maxbatchsize)  
   * [Ai.Embeddings.MaxConcurrentBatches](../../server/configuration/ai-integration-configuration#ai.embeddings.maxconcurrentbatches)  
   * [Ai.Embeddings.MaxFallbackTimeInSec](../../server/configuration/ai-integration-configuration#ai.embeddings.maxfallbacktimeinsec)  


{NOTE/}

---

{PANEL: Ai.Embeddings.MaxBatchSize}

The maximum number of documents processed in a single batch by an embeddings generation task.  
Higher values may improve throughput but can increase latency and require more resources and higher limits from the embeddings generation service.

- **Type**: `int`
- **Default**: `128`
- **Scope**: Server-wide or per database

{PANEL/}

{PANEL: Ai.Embeddings.MaxConcurrentBatches}

The maximum number of query embedding batches that can be processed concurrently.  
This setting controls the degree of parallelism when sending query embedding requests to AI providers.  
Higher values may improve throughput but can increase resource usage and may trigger rate limits.

- **Type**: `int`
- **Default**: `4`
- **Scope**: Server-wide or per database

{PANEL/}

{PANEL: Ai.Embeddings.MaxFallbackTimeInSec}

The maximum time (in seconds) the embeddings generation task remains suspended (fallback mode) following a connection failure to the embeddings generation service.
Once this time expires, the system will retry the connection automatically.

- **Type**: `int`
- **Default**: `60 * 15`
- **Scope**: Server-wide or per database

{PANEL/}
