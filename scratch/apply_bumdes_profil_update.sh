#!/bin/bash
echo "🚀 Applying BUMDes profil organ columns update to tenant databases..."

# Array of tenant databases to update
DATABASES=("xosid_tenant_demo" "xosid_tenant_bumidaya")

for DB in "${DATABASES[@]}"; do
    echo "Applying update schema to $DB..."
    docker exec -i xosid_neural_db mysql -uroot -ppassword -D "$DB" < scratch/update_bumdes_profil_schema.sql
    if [ $? -eq 0 ]; then
        echo "✅ Successfully applied BUMDes profil organ columns to $DB!"
    else
        echo "❌ Failed to apply BUMDes profil organ columns to $DB!"
    fi
done

echo "🎉 All database migrations complete!"
