const fs = require('fs');
const path = require('path');

function walk(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(function(file) {
        file = path.join(dir, file);
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) { 
            results = results.concat(walk(file));
        } else { 
            if (file.endsWith('.js') || file.endsWith('.jsx')) {
                results.push(file);
            }
        }
    });
    return results;
}

const files = walk('./resources/js');

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    let original = content;

    // 1. Intl.NumberFormat
    content = content.replace(/new Intl\.NumberFormat\('en-US'\)\.format\((.*?)\)/g, "new Intl.NumberFormat('en-US', {maximumFractionDigits: 0}).format(Math.round($1))");
    content = content.replace(/new Intl\.NumberFormat\('en-US',\s*\{\s*style:\s*'currency',\s*currency:\s*'EGP'\s*\}\)\.format\((.*?)\)/g, "new Intl.NumberFormat('en-US', { style: 'currency', currency: 'EGP', maximumFractionDigits: 0 }).format(Math.round($1))");
    
    // 2. Number(...).toLocaleString('en-US')
    content = content.replace(/Number\((.*?)\)\.toLocaleString\('en-US'\)/g, "Math.round(Number($1)).toLocaleString('en-US')");
    
    // 3. parseFloat(...).toLocaleString('en-US')
    content = content.replace(/parseFloat\((.*?)\)\.toLocaleString\('en-US'\)/g, "Math.round(parseFloat($1)).toLocaleString('en-US')");
    
    // 4. {val.toLocaleString('en-US')}
    content = content.replace(/\{([a-zA-Z0-9_.]+)\.toLocaleString\('en-US'\)\}/g, "{Math.round($1).toLocaleString('en-US')}");

    // Direct variables before ج.م : {sub.price} ج.م -> {Math.round(sub.price)} ج.م
    content = content.replace(/\{([a-zA-Z0-9_.]+)\}\s*(ج\.م|جنيه)/g, "{Math.round($1)} $2");
    
    // template literals like `${theme.price} ${theme.currency || 'ج.م'}`
    content = content.replace(/\$\{([a-zA-Z0-9_.]+)\}\s*\$\{([a-zA-Z0-9_.\|'\s]+)\}/g, "${Math.round($1)} ${$2}");
    
    // `${theme.price} ج.م`
    content = content.replace(/\$\{([a-zA-Z0-9_.]+)\}\s*(ج\.م|جنيه)/g, "${Math.round($1)} $2");
    
    // Add Math.round for stats.avg directly: >{stats.avg} ج.م<  -- caught by \{...\}
    
    // Avoid double Math.round
    content = content.replace(/Math\.round\(Math\.round\((.*?)\)\)/g, "Math.round($1)");

    if (original !== content) {
        fs.writeFileSync(file, content);
        console.log("Updated", file);
    }
});
