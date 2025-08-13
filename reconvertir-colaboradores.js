import fs from 'fs';
import path from 'path';
import sharp from 'sharp';

const carpeta = './src/img/colaboradores/';
const archivos = fs.readdirSync(carpeta);

for (const archivo of archivos) {
   const ext = path.extname(archivo).toLowerCase();
   if (ext === '.jpg' || ext === '.jpeg') {
      const entrada = path.join(carpeta, archivo);
      const temporal = path.join(carpeta, `${path.parse(archivo).name}_tmp.jpg`);

      sharp(entrada)
         .jpeg({quality: 90})
         .toFile(temporal)
         .then(() => {
            fs.renameSync(temporal, entrada);
            console.log(`✅ Reconvertido: ${archivo}`);
         })
         .catch((err) => {
            console.error(`❌ Error con ${archivo}:`, err.message);
         });
   }
}
