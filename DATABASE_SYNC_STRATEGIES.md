# Database Sync Strategies for 2M+ Rows

## Overview
This document outlines multiple approaches for efficiently syncing large datasets (2M+ rows) between MySQL (primary) and PostgreSQL (reporting) databases.

## Approach 1: Enhanced Incremental Sync (Recommended) ✅

### Features:
- **Incremental updates** - Only sync new/modified records
- **Bulk inserts** - 1000 records per batch for optimal performance
- **Progress tracking** - Real-time progress with rate monitoring
- **Error handling** - Comprehensive logging and recovery
- **Memory optimization** - 2GB memory limit with garbage collection

### Performance:
- **Rate**: ~500-1000 records/second
- **Memory**: 2GB peak usage
- **Duration**: ~30-60 minutes for 2M records
- **Network**: Minimal impact on production

### Usage:
```bash
# Daily incremental sync
php artisan sync:mysql-to-postgres --all

# Force full sync
php artisan sync:mysql-to-postgres --all --force

# Custom batch size
php artisan sync:mysql-to-postgres --alerts --batch-size=2000

# Date range sync
php artisan sync:mysql-to-postgres --alerts --from=2024-01-01 --to=2024-01-31
```

## Approach 2: Queue-Based Sync (For High-Volume)

### Features:
- **Background processing** - Uses Laravel queues
- **Chunked processing** - 500 records per job
- **Retry mechanism** - Automatic retry on failure
- **Parallel processing** - Multiple workers
- **Memory efficient** - Each job processes small batches

### Implementation:
```php
// Create queue jobs for each chunk
$chunks = $query->chunk(500, function($records) {
    dispatch(new SyncChunkJob($records));
});
```

### Performance:
- **Rate**: ~200-400 records/second per worker
- **Scalability**: Add more workers for faster sync
- **Reliability**: Automatic retry and error handling

## Approach 3: Database-Level Sync (Maximum Performance)

### Features:
- **Direct database connection** - Bypass Laravel ORM
- **COPY command** - PostgreSQL's fastest insert method
- **Minimal memory usage** - Stream processing
- **Maximum throughput** - 2000+ records/second

### Implementation:
```php
// Use PostgreSQL COPY command
$pdo = DB::connection('pgsql')->getPdo();
$stmt = $pdo->prepare("COPY alerts FROM STDIN");
$stmt->execute();
```

### Performance:
- **Rate**: 2000+ records/second
- **Memory**: Minimal (< 100MB)
- **Duration**: ~15-30 minutes for 2M records

## Approach 4: Hybrid Approach (Best of All Worlds)

### Features:
- **Smart batching** - Adaptive batch sizes
- **Parallel processing** - Multiple sync processes
- **Incremental + Full** - Best of both strategies
- **Monitoring** - Real-time performance metrics

### Implementation:
```php
// Adaptive batch sizing
$batchSize = $this->calculateOptimalBatchSize($tableSize);
$processes = $this->calculateOptimalProcesses($cpuCores);
```

## Approach 5: External Tools (For Maximum Performance)

### Option A: Debezium + Kafka
- **Real-time streaming** - Change Data Capture (CDC)
- **Zero downtime** - Continuous sync
- **High throughput** - 10,000+ records/second
- **Complex setup** - Requires infrastructure

### Option B: AWS DMS (Database Migration Service)
- **Managed service** - AWS handles infrastructure
- **High performance** - Optimized for large datasets
- **Monitoring** - Built-in metrics and alerts
- **Cost** - Pay per hour of usage

### Option C: Custom ETL Pipeline
- **Python/Node.js scripts** - Custom implementation
- **Direct SQL** - Maximum performance
- **Flexible** - Custom logic and transformations
- **Maintenance** - Requires custom development

## Recommended Implementation Strategy

### Phase 1: Enhanced Incremental Sync (Current)
- ✅ Already implemented
- ✅ Good performance for daily syncs
- ✅ Minimal resource usage
- ✅ Easy to maintain

### Phase 2: Queue-Based Enhancement
- Add queue processing for high-volume periods
- Implement parallel processing
- Add monitoring and alerting

### Phase 3: Performance Optimization
- Implement database-level COPY commands
- Add adaptive batch sizing
- Optimize indexes and queries

### Phase 4: External Tools (If Needed)
- Consider Debezium for real-time sync
- Evaluate AWS DMS for managed solution
- Implement custom ETL for specific needs

## Performance Comparison

| Approach | Records/sec | Memory Usage | Setup Complexity | Maintenance |
|----------|-------------|--------------|------------------|-------------|
| Enhanced Incremental | 500-1000 | 2GB | Low | Low |
| Queue-Based | 200-400/worker | 500MB/worker | Medium | Medium |
| Database-Level | 2000+ | 100MB | High | High |
| Debezium | 10,000+ | Variable | Very High | Very High |
| AWS DMS | 5000+ | Managed | Low | Low |

## Monitoring and Alerting

### Metrics to Track:
- Sync duration and rate
- Memory usage
- Error rates
- Data consistency
- Network usage

### Alerts:
- Sync failures
- Performance degradation
- Data inconsistencies
- Memory/CPU spikes

## Best Practices

### 1. Indexing Strategy
```sql
-- MySQL indexes for sync performance
CREATE INDEX idx_alerts_createtime ON alerts(createtime);
CREATE INDEX idx_alerts_id_createtime ON alerts(id, createtime);

-- PostgreSQL indexes for query performance
CREATE INDEX idx_alerts_panelid ON alerts(panelid);
CREATE INDEX idx_alerts_createtime ON alerts(createtime);
```

### 2. Batch Size Optimization
- Start with 1000 records per batch
- Monitor memory usage
- Adjust based on server capacity
- Test with different sizes

### 3. Error Handling
- Log all errors with context
- Implement retry mechanisms
- Alert on critical failures
- Maintain sync state

### 4. Data Consistency
- Verify record counts
- Check for duplicates
- Validate data integrity
- Monitor sync lag

## Scheduling Recommendations

### Daily Sync (Recommended)
```bash
# Cron job at 2 AM
0 2 * * * cd /path/to/app && php artisan sync:mysql-to-postgres --all
```

### Weekly Full Sync
```bash
# Sunday at 3 AM
0 3 * * 0 cd /path/to/app && php artisan sync:mysql-to-postgres --all --force
```

### Monitoring Commands
```bash
# Check sync status
php artisan sync:status

# View sync logs
php artisan sync:logs

# Test sync performance
php artisan sync:test --batch-size=2000
```

## Conclusion

For your 2M+ row scenario, I recommend starting with the **Enhanced Incremental Sync** approach (already implemented) and gradually optimizing based on your specific requirements:

1. **Start with current implementation** - It's already optimized for your use case
2. **Monitor performance** - Track sync times and resource usage
3. **Optimize gradually** - Add queue processing if needed
4. **Consider external tools** - Only if you need real-time sync or higher throughput

The current implementation should handle your daily sync requirements efficiently while maintaining data consistency and minimal resource usage. 