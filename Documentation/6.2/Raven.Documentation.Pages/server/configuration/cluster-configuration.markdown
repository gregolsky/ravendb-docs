# Configuration: Cluster
---

{NOTE: }

* **Avoid using different cluster configurations across nodes:**  
  Configuration mismatches can lead to interaction problems between nodes.

* If you must set different configurations for individual nodes,
  we recommend testing the setup in a development environment first to ensure proper interaction between all nodes.

{NOTE/}

{NOTE: }

* In this page:
  * Server-wide scope:  
    [Cluster.CompareExchangeExpiredDeleteFrequencyInSec](../../server/configuration/cluster-configuration#cluster.compareexchangeexpireddeletefrequencyinsec)  
    [Cluster.CompareExchangeTombstonesCleanupIntervalInMin](../../server/configuration/cluster-configuration#cluster.compareexchangetombstonescleanupintervalinmin)  
    [Cluster.ElectionTimeoutInMs](../../server/configuration/cluster-configuration#cluster.electiontimeoutinms)  
    [Cluster.HardDeleteOnReplacement](../../server/configuration/cluster-configuration#cluster.harddeleteonreplacement)  
    [Cluster.LogHistoryMaxEntries](../../server/configuration/cluster-configuration#cluster.loghistorymaxentries)  
    [Cluster.MaxChangeVectorDistance](../../server/configuration/cluster-configuration#cluster.maxchangevectordistance)  
    [Cluster.MaxClusterTransactionCompareExchangeTombstoneCheckIntervalInMin](../../server/configuration/cluster-configuration#cluster.maxclustertransactioncompareexchangetombstonecheckintervalinmin)  
    [Cluster.MaxSizeOfSingleRaftCommandInMb](../../server/configuration/cluster-configuration#cluster.maxsizeofsingleraftcommandinmb)  
    [Cluster.MaximalAllowedClusterVersion](../../server/configuration/cluster-configuration#cluster.maximalallowedclusterversion)  
    [Cluster.OnErrorDelayTimeInMs](../../server/configuration/cluster-configuration#cluster.onerrordelaytimeinms)  
    [Cluster.OperationTimeoutInSec](../../server/configuration/cluster-configuration#cluster.operationtimeoutinsec)  
    [Cluster.ReceiveFromWorkerTimeoutInMs](../../server/configuration/cluster-configuration#cluster.receivefromworkertimeoutinms)  
    [Cluster.StatsStabilizationTimeInSec](../../server/configuration/cluster-configuration#cluster.statsstabilizationtimeinsec)  
    [Cluster.SupervisorSamplePeriodInMs](../../server/configuration/cluster-configuration#cluster.supervisorsampleperiodinms)  
    [Cluster.TcpReceiveBufferSizeInBytes](../../server/configuration/cluster-configuration#cluster.tcpreceivebuffersizeinbytes)  
    [Cluster.TcpSendBufferSizeInBytes](../../server/configuration/cluster-configuration#cluster.tcpsendbuffersizeinbytes)  
    [Cluster.TcpTimeoutInMs](../../server/configuration/cluster-configuration#cluster.tcptimeoutinms)  
    [Cluster.TimeBeforeAddingReplicaInSec](../../server/configuration/cluster-configuration#cluster.timebeforeaddingreplicainsec)  
    [Cluster.TimeBeforeMovingToRehabInSec](../../server/configuration/cluster-configuration#cluster.timebeforemovingtorehabinsec)  
    [Cluster.TimeBeforeRotatingPreferredNodeInSec](../../server/configuration/cluster-configuration#cluster.timebeforerotatingpreferrednodeinsec)  
    [Cluster.WorkerSamplePeriodInMs](../../server/configuration/cluster-configuration#cluster.workersampleperiodinms)  
  * Server-wide, or database scope:   
    [Cluster.DisableAtomicDocumentWrites](../../server/configuration/cluster-configuration#cluster.disableatomicdocumentwrites)  
    [Cluster.MaxClusterTransactionBatchSize](../../server/configuration/cluster-configuration#cluster.maxclustertransactionbatchsize)  

{NOTE/}

---

{PANEL: Cluster.CompareExchangeExpiredDeleteFrequencyInSec}

Time (in seconds) between cleanup of expired compare exchange items.

- **Type**: `int`
- **Default**: `60`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.CompareExchangeTombstonesCleanupIntervalInMin}

Time (in minutes) between cleanup of compare exchange tombstones.

- **Type**: `int`
- **Default**: `10`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.ElectionTimeoutInMs}

Timeout (in milliseconds) within which the node expects to receive a heartbeat from the leader.

- **Type**: `int`
- **Default**: `300`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.HardDeleteOnReplacement}

Set hard/soft delete for a database that was removed by the observer from the cluster topology in order to maintain the replication factor.

- **Type**: `bool`
- **Default**: `true`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.LogHistoryMaxEntries}

Maximum number of log entries to keep in the history log table.

- **Type**: `int`
- **Default**: `2048`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.MaxChangeVectorDistance}

Exceeding the allowed change vector distance between two nodes will move the lagged node to rehab.

- **Type**: `long`
- **Default**: `65536`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.MaxClusterTransactionCompareExchangeTombstoneCheckIntervalInMin}

The maximum interval (in minutes) between checks for compare exchange tombstones that are performed by the cluster-wide transaction mechanism.

- **Type**: `int`
- **Default**: `5`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.MaxSizeOfSingleRaftCommandInMb}

EXPERT ONLY:  
The maximum allowed size (in megabytes) for a single raft command.

- **Type**: `int`
- SizeUnit(SizeUnit.Megabytes)
- **Default**: `128`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.MaximalAllowedClusterVersion}

EXPERT ONLY:  
If exceeded, restrict the cluster to the specified version.

- **Type**: `int?`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.OnErrorDelayTimeInMs}

How long the maintenance supervisor waits (in milliseconds) after receiving an exception from a worker before retrying.

- **Type**: `int`
- **Default**: `5000`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.OperationTimeoutInSec}

As a cluster node, how long (in seconds) to wait before timing out an operation between two cluster nodes.

- **Type**: `int`
- **Default**: `15`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.ReceiveFromWorkerTimeoutInMs}

How long the maintenance supervisor waits (in milliseconds) for a response from a worker before timing out.

- **Type**: `int`
- **Default**: `5000`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.StatsStabilizationTimeInSec}

How long to wait (in seconds) for cluster stats to stabilize after a database topology change.

- **Type**: `int`
- **Default**: `5`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.SupervisorSamplePeriodInMs}

How long the maintenance supervisor waits (in milliseconds) between sampling the information received from the nodes.

- **Type**: `int`
- **Default**: `1000`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.TcpReceiveBufferSizeInBytes}

The size (in bytes) of the TCP connection receive buffer.

- **Type**: `int`
- **Default**: `32 * 1024`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.TcpSendBufferSizeInBytes}

The size (in bytes) of the TCP connection send buffer.

- **Type**: `int`
- **Default**: `32 * 1024`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.TcpTimeoutInMs}

TCP connection read/write timeout (in milliseconds).

- **Type**: `int`
- **Default**: `15_000`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.TimeBeforeAddingReplicaInSec}

The time (in seconds) a database instance must be in a good and responsive state before we add a replica to match the replication factor.

- **Type**: `int`
- **Default**: `900`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.TimeBeforeMovingToRehabInSec}

The grace period (in seconds) we give a node before it is moved to rehab.

- **Type**: `int`
- **Default**: `60`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.TimeBeforeRotatingPreferredNodeInSec}

The grace period (in seconds) we give the preferred node before moving it to the end of the members list.

- **Type**: `int`
- **Default**: `5`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.WorkerSamplePeriodInMs}

The time (in milliseconds) between sampling database information and sending it to the maintenance supervisor.

- **Type**: `int`
- **Default**: `500`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Cluster.DisableAtomicDocumentWrites}

EXPERT ONLY:  
Disable automatic atomic writes with cluster write transactions.  
If set to _true_, will only consider explicitly added compare exchange values to validate cluster wide transactions.

- **Type**: `bool`
- **Default**: `false`
- **Scope**: Server-wide or per database

{PANEL/}

{PANEL: Cluster.MaxClusterTransactionBatchSize}

EXPERT ONLY:  
Specifies the maximum size of the cluster transaction batch to be executed on the database at once.

- **Type**: `int`
- **Default**: `256`
- **Scope**: Server-wide or per database

{PANEL/}
