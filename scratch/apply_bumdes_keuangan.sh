#!/bin/bash
echo "🚀 Creating BUMDes Keuangan & Akuntansi tables in tenant databases..."

DATABASES=("xosid_tenant_demo" "xosid_tenant_bumidaya")

for DB in "${DATABASES[@]}"; do
    echo "Applying schema to $DB..."
    docker exec -i xosid_neural_db mysql -uroot -ppassword -D "$DB" < scratch/create_bumdes_keuangan_tables.sql
    if [ $? -eq 0 ]; then
        echo "✅ Successfully applied keuangan schema to $DB!"
    else
        echo "❌ Failed to apply keuangan schema to $DB!"
    fi
done

echo "🎉 All keuangan database schemas applied successfully!"
