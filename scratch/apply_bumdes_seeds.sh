#!/bin/bash
echo "🚀 Seeding BUMDes categories and products dummy data..."

DATABASES=("xosid_tenant_demo" "xosid_tenant_bumidaya")

for DB in "${DATABASES[@]}"; do
    echo "Applying seed data to $DB..."
    docker exec -i xosid_neural_db mysql -uroot -ppassword -D "$DB" < scratch/seed_bumdes_data.sql
    if [ $? -eq 0 ]; then
        echo "✅ Successfully seeded $DB!"
    else
        echo "❌ Failed to seed $DB!"
    fi
done

echo "🎉 All database seeding complete!"
