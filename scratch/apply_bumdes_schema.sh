#!/bin/bash
echo "🚀 Applying BUMDes database schema to tenant databases..."

# Array of tenant databases to update
DATABASES=("xosid_tenant_demo" "xosid_tenant_bumidaya")

for DB in "${DATABASES[@]}"; do
    echo "Applying schema to $DB..."
    docker exec -i xosid_neural_db mysql -uroot -ppassword -D "$DB" < scratch/create_bumdes_tables.sql
    if [ $? -eq 0 ]; then
        echo "✅ Successfully applied BUMDes schema to $DB!"
    else
        echo "❌ Failed to apply BUMDes schema to $DB!"
    fi
done

echo "🎉 All database migrations complete!"
