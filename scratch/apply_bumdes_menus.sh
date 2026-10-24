#!/bin/bash
echo "🚀 Inserting BUMDes menus into setting_modul table..."

# Array of tenant databases to update
DATABASES=("xosid_tenant_demo" "xosid_tenant_bumidaya")

for DB in "${DATABASES[@]}"; do
    echo "Applying menus to $DB..."
    docker exec -i xosid_neural_db mysql -uroot -ppassword -D "$DB" < scratch/insert_bumdes_menus.sql
    if [ $? -eq 0 ]; then
        echo "✅ Successfully inserted BUMDes menus into $DB!"
    else
        echo "❌ Failed to insert BUMDes menus into $DB!"
    fi
done

echo "🎉 Menu integration complete!"
