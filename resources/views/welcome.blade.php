<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$nama_aplikasi}}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
            overflow-x: hidden;
        }

        #canvas-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            padding: 24px 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 600;
            color: #00DCDD;
        }

        .logo i {
            font-size: 28px;
        }

        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            text-align: center;
            padding: 40px 0;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #00DCDD, #009B9C);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.25rem;
            max-width: 700px;
            margin-bottom: 40px;
            color: #64748b;
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 16px 40px;
            font-size: 1.25rem;
            font-weight: 500;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00DCDD, #009B9C);
            color: white;
            box-shadow: 0 10px 25px rgba(0, 220, 221, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 220, 221, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            color: #00DCDD;
            border: 2px solid #00DCDD;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .btn-secondary:hover {
            transform: translateY(-5px);
            background: white;
            box-shadow: 0 10px 20px rgba(0, 220, 221, 0.2);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 80px;
            padding: 40px 0;
        }

        .feature {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .feature:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(0, 220, 221, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .feature-icon i {
            font-size: 30px;
            color: #00DCDD;
        }

        .feature h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #1e293b;
        }

        .feature p {
            color: #64748b;
            line-height: 1.6;
        }

        footer {
            text-align: center;
            padding: 40px 0;
            margin-top: 60px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
                padding: 0 20px;
            }
            
            .button-group {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
            
            .features {
                grid-template-columns: 1fr;
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .btn {
                padding: 14px 30px;
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <canvas id="canvas-bg"></canvas>
    
    <div class="container">
        <header>
            <div class="logo">
                <img src="{{ $file_logo }}" alt="E-SKM Logo" style="height:40px; width:auto;">
                <span>{{$name}}</span>
            </div>
        </header>
        
        <section class="hero">
            <h1>Halo! Selamat Datang di {{$nama_aplikasi}}</h1>
            <p>Platform survey kepuasan masyarakat yang simpel dan mudah digunakan. Berikan penilaian Anda dengan cepat dan membantu kami untuk terus meningkatkan pelayanan.</p>
            
            <div class="button-group">
                <a href="https://skm.tanjungpinangkota.go.id/" class="btn btn-primary">
                    <i class="fas fa-clipboard-check"></i>
                    Isi Survey Sekarang
                </a>
                <a href="{{route('filament.admin.auth.login')}}" class="btn btn-secondary">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk ke Akun
                </a>
            </div>
        </section>
        
        <footer>
            <p>&copy; 2025 {{$copyright}}</p>
        </footer>
    </div>

    <script>
        // Canvas dengan polygonal lines
        const canvas = document.getElementById('canvas-bg');
        const ctx = canvas.getContext('2d');
        
        // Set canvas size
        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
        
        // Warna utama dan variasi
        const primaryColor = '#00DCDD';
        const colorVariants = [
            '#00DCDD', '#00C4C4', '#00ACAC', '#009B9C', '#008B8C'
        ];
        
        // Points untuk polygonal lines
        const points = [];
        const pointCount = 15;
        
        // Generate random points
        for (let i = 0; i < pointCount; i++) {
            points.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                size: Math.random() * 3 + 1,
                color: colorVariants[Math.floor(Math.random() * colorVariants.length)]
            });
        }
        
        // Fungsi untuk menggambar polygonal lines
        function drawPolygonalLines() {
            // Clear canvas dengan transparansi untuk efek trail
            ctx.fillStyle = 'rgba(248, 250, 252, 0.05)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Update dan gambar points
            points.forEach(point => {
                // Update posisi
                point.x += point.vx;
                point.y += point.vy;
                
                // Boundary check
                if (point.x < 0 || point.x > canvas.width) point.vx *= -1;
                if (point.y < 0 || point.y > canvas.height) point.vy *= -1;
                
                // Gambar point
                ctx.beginPath();
                ctx.arc(point.x, point.y, point.size, 0, Math.PI * 2);
                ctx.fillStyle = point.color;
                ctx.fill();
            });
            
            // Gambar lines antara points yang berdekatan
            ctx.strokeStyle = primaryColor;
            ctx.lineWidth = 0.5;
            
            for (let i = 0; i < points.length; i++) {
                for (let j = i + 1; j < points.length; j++) {
                    const dx = points[i].x - points[j].x;
                    const dy = points[i].y - points[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    // Hanya gambar line jika points cukup dekat
                    if (distance < 150) {
                        ctx.beginPath();
                        ctx.moveTo(points[i].x, points[i].y);
                        ctx.lineTo(points[j].x, points[j].y);
                        ctx.stroke();
                    }
                }
            }
            
            requestAnimationFrame(drawPolygonalLines);
        }
        
        // Mulai animasi
        drawPolygonalLines();
    </script>
</body>
</html>