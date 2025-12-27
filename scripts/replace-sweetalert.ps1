# PowerShell script to replace confirm() with SweetAlert functions
# Run from siakad-app directory

$viewsPath = "resources\views"

# Define replacements patterns
$replacements = @(
    # Alpine.js patterns - Delete confirmations
    @{
        Pattern = '@submit.prevent="if\(confirm\(''Apakah Anda yakin ingin menghapus ([^'']+)''\)\) \$el\.submit\(\)"'
        Replace = '@submit.prevent="window.swalConfirmDelete($event, ''$1'')"'
    },
    @{
        Pattern = '@submit.prevent="if\(confirm\(''Yakin ingin menghapus ([^'']+)''\)\) \$el\.submit\(\)"'
        Replace = '@submit.prevent="window.swalConfirmDelete($event, ''$1'')"'
    },
    
    # Standard onsubmit delete patterns
    @{
        Pattern = 'onsubmit="return confirm\(''Apakah Anda yakin ingin menghapus ([^'']+)''\)"'
        Replace = 'onsubmit="return confirmDelete(this, ''$1'')"'
    },
    @{
        Pattern = 'onsubmit="return confirm\(''Yakin ingin menghapus ([^'']+)''\)"'
        Replace = 'onsubmit="return confirmDelete(this, ''$1'')"'
    },
    @{
        Pattern = 'onsubmit="return confirm\(''Hapus ([^'']+)''\)"'
        Replace = 'onsubmit="return confirmDelete(this, ''$1'')"'
    },
    
    # onclick delete patterns
    @{
        Pattern = 'onclick="return confirm\(''Hapus ([^'']+)''\)"'
        Replace = 'onclick="return confirmDelete(this.form, ''$1'')"'
    },
    @{
        Pattern = 'onclick="return confirm\(''Restore ([^'']+)''\)"'
        Replace = 'onclick="return confirmRestore(this.form, ''$1'')"'
    },
    
    # Restore patterns
    @{
        Pattern = 'onsubmit="return confirm\(''Restore ([^'']+)''\)"'
        Replace = 'onsubmit="return confirmRestore(this, ''$1'')"'
    },
    
    # Verify patterns  
    @{
        Pattern = 'onsubmit="return confirm\(''Verifikasi ([^'']+)''\)"'
        Replace = 'onsubmit="return confirmVerify(this, ''$1'')"'
    },
    
    # Approve patterns
    @{
        Pattern = 'onsubmit="return confirm\(''Approve ([^'']+)''\)"'
        Replace = 'onsubmit="return confirmApprove(this, ''$1'')"'
    }
)

Write-Host "Starting SweetAlert replacement..."

Get-ChildItem -Path $viewsPath -Recurse -Filter "*.blade.php" | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $modified = $false
    
    foreach ($r in $replacements) {
        if ($content -match $r.Pattern) {
            $content = $content -replace $r.Pattern, $r.Replace
            $modified = $true
        }
    }
    
    if ($modified) {
        Set-Content -Path $_.FullName -Value $content -NoNewline
        Write-Host "Updated: $($_.FullName)"
    }
}

Write-Host "Done!"
